<?php

namespace App\Http\Controllers;


use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\MiniProgramPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeChatController extends Controller
{
    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getJssdkConfig(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'apis' => 'filled|array',
            'open_tags' => 'filled|array|in:wx-open-launch-weapp,wx-open-launch-app,wx-open-subscribe,wx-open-audio',
            'beta' => 'in:true,false',
            'debug' => 'in:true,false',
            'url' => ['url', new Redirect()],
        ]);

        $apis = (array)$request->input('apis', []);
        $openTags = (array)$request->input('open_tags', []);

        return \response()->json(
            app('wechat.official_account')->jssdk->getConfigArray(
                $apis,
                !app()->isProduction() && $request->has('debug'),
                $request->input('beta', false),
                $openTags,
                $request->input('url')
            )
        );
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function server(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $client = \app('wechat.official_account');
        $server = $client->server;
        $message = $server->getMessage();

        if ('text' === ($message['MsgType'] ?? null)) {
            $this->replayKeywords($message);
        }

        if (!isset($message['EventKey'])) {
            $openId = $message['FromUserName'];

            // 直接关注
            if ('subscribe' === ($message['Event'] ?? null)) {
                $this->pushSubscribeMessage($openId);

                $officialUser = $client->user->get($openId);

                // 判断公众号是否绑定
                $hasBoundOfficialAccount = Profile::query()->where('platform', OfficialAccount::getName())->where(
                    fn ($query) => $query->where('platform_id', $openId)->orWhere('union_id', $officialUser['unionid'])
                )->exists();

                if (!$hasBoundOfficialAccount) {
                    $hasBoundMiniApp = Profile::query()->where('platform', MiniApp::getName())->where('union_id', $officialUser['unionid'])->first();

                    if ($hasBoundMiniApp) {
                        Profile::query()->create([
                            'user_id' => $hasBoundMiniApp->user_id,
                            'platform' => OfficialAccount::getName(),
                            'platform_id' => $openId,
                            'union_id' => $officialUser['unionid'],
                            'name' => \substr($openId, 0, 8),
                        ]);
                    }
                }
            }

            // 取消关注
            if ('unsubscribe' === ($message['Event'] ?? null)) {
                $userId = Profile::platformId2UserId('wechat.official_account', $message['FromUserName']);

                User::saveUserExtends($userId, ['subscribed_scene' => null, 'unsubscribed_at' => \now()], 'unsubscribe');
            }

            return $server->serve();
        }

        $this->handle($client, $message['EventKey'], $message);

        return $server->serve();
    }

    // 推送加群二维码
    protected function pushJoinGroupChatQrCode(string $openid): Image
    {
        $message = new Image('Oe5oT9dwYjVblWvAtuwC9Tesperhbr1FYKp9FlMksyU');
        app('wechat.official_account')->customer_service->message($message)->to($openid)->send();

        return $message;
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    protected function pushSubscribeMessage(string $openid, bool $eventSubscribed = false): ?Text
    {
        $userInfo = app('wechat.official_account')->user->get($openid);

        if (!isset($userInfo['nickname'])) {
            return null;
        }

        $miniprogramAppId = \config('services.wechat.miniapp.app_id');

        // 通过活动海报关注
        if ($eventSubscribed) {
            $content = "亲爱的{$userInfo['nickname']}，欢迎关注腾讯 CoDesign 设计协作平台！点击下方立即体验 CoDesign 小程序";
        } else {
            $content = \sprintf(
                "%s\n\n%s\n\n%s\n\n%s",
                "亲爱的{$userInfo['nickname']}，欢迎关注腾讯 CoDesign 设计协作平台！",
                "戳这里→<a href='http://www.qq.com' data-miniprogram-appid='$miniprogramAppId' data-miniprogram-path='pages/workspace/index'>小程序也能查看设计稿</a>",
                "戳这里→<a href='http://codesign.qq.com/'>快速了解CoDesign</a>",
                "如需帮助，请点击<a href='https://cloud.tencent.com/online-service?from=intro_codesign'>联系客服</a>"
            );
        }

        $message = new Text($content);

        app('wechat.official_account')->customer_service->message($message)->to($openid)->send();

        return $message;
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    protected function pushInvitationLink(string $openid, ?Invitation $invitation): ?MiniProgramPage
    {
        if (!$invitation) {
            return null;
        }

        $message = new MiniProgramPage([
            'title' => '邀请你使用 CoDesign',
            'appid' => \config('services.wechat.miniapp.app_id'),
            'pagepath' => 'pages/login/index?invite=' . $invitation->signature,
            'thumb_media_id' => \app()->isProduction() ? 'Oe5oT9dwYjVblWvAtuwC9UZD7otpbU5_GzV3WJo2Of8' : '5uXB8aIfKq0iuSOAVpMWEGGr5P9slzVLqaYkVlTf5s4',
        ]);

        app('wechat.official_account')->customer_service->message($message)->to($openid)->send();

        return $message;
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    protected function handle($client, ?string $eventKey, $message)
    {
        if (($eventKey && \Str::startsWith($eventKey, 'qrscene_')) || (\is_null($eventKey) && 'subscribe' === ($message['Event'] ?? null))) {
            $this->pushSubscribeMessage($message['FromUserName']);
//            $this->pushJoinGroupChatQrCode($message['FromUserName']);
        }

        if ($eventKey && \Str::startsWith($eventKey, ['USER_SUBSCRIBE', 'qrscene_USER_SUBSCRIBE'])) {
            return $this->subscribe($client, $message, $eventKey);
        }

        $tagName = match ($eventKey) {
            'qrscene_HOME_FOOTER' => '官网底部二维码',
            'qrscene_CONFERENCE' => '发布会',
            'qrscene_USER_LOGGED' => '登录后关注',
            'qrscene_MINIAPP_BANNER' => '小程序',
            'qrscene_RED_PACKET_COVER' => '2022红包封面-新关注',
            'RED_PACKET_COVER' => '2022红包封面-已关注',
            default => '未识别关注渠道',
        };

        if ($tagName && 'subscribe' === ($message['Event'] ?? null)) {
            $this->tagging($client, $message, $tagName);
        }

        $userId = Profile::platformId2UserId('wechat.official_account', $message['FromUserName']);

        switch ($eventKey) {
            case 'CONFERENCE':
//                $this->pushJoinGroupChatQrCode($message['FromUserName']);
                break;
            case 'qrscene_USER_LOGGED':
                // 未订阅时扫码
                if ($userId) {
                    Horizon::report('home.scan', $userId);
                }
                break;
        }

        if ($userId && 'subscribe' === ($message['Event'] ?? null)) {
            User::saveUserExtends($userId, ['subscribed_scene' => $eventKey, 'subscribed_at' => \now(), 'unsubscribed_at' => null]);
        }
    }

    protected function tagging($client, $message, $tagName)
    {
        $userTag = $client->user_tag;
        $tags = \collect(\data_get($userTag->list(), 'tags'));

        if ($tags->where('name', $tagName)->isEmpty()) {
            $tagId = \data_get($userTag->create($tagName), 'tag.id');
        } else {
            $tagId = $tags->where('name', $tagName)->first()['id'] ?? null;
        }

        if ($tagId && isset($message['FromUserName'])) {
            $userTag->tagUsers((array)$message['FromUserName'], $tagId);
        }
    }

    protected function subscribe($client, $message, $eventKey): bool
    {
        $openId = $message['FromUserName'];

        $value = explode('_', \str_replace(['USER_SUBSCRIBE_', 'qrscene_'], '', $eventKey));

        if (count($value) !== 2) {
            return false;
        }

        list($scene, $userId) = $value;

        $tagName = match ($scene) {
            'notification-setting' => '消息中心-消息设置',
            'notification-banner' => '消息中心-关注Banner',
            'account-popup' => '左下角个人菜单',
            'notification-modal' => '消息触达-特性弹窗',
            default => $scene,
        };

        if ($tagName && 'subscribe' === ($message['Event'] ?? null)) {
            $this->tagging($client, $message, $tagName);
        }

        /* @var $user User */
        $user = User::query()->find(\decode_hashid($userId));

        if (!$user) {
            return false;
        }

        $officialUser = $client->user->get($openId);

        $hasBound = Profile::query()->whereIn('platform', [OfficialAccount::getName(), MiniApp::getName()])
            ->where('union_id', $officialUser['unionid'])
            ->where('user_id', '<>', $user->id)
            ->exists();

        // 当前微信账号已被非当前用户绑定
        if ($hasBound) {
            app('wechat.official_account')->customer_service->message(new Text('你的微信已绑定其它CoDesign账号，需更换绑定成当前CoDesign账号后，才可收到消息通知。 更改方式：Web端进入 账号设置>账号绑定>微信绑定。'))->to($openId)->send();
            return false;
        }

        $officialAccount = $user->profiles()->where('platform', OfficialAccount::getName())->first();
        $miniAccount = $user->profiles()->where('platform', MiniApp::getName())->first();

        // 当前关注公众号微信与之前绑定的不一致 || 当前关注公众号微信与小程序所在微信账号不一致
        if (($officialAccount && $officialAccount->platform_id !== $openId) || (!$officialAccount && $miniAccount && ($unionId = $miniAccount->union_id) && $officialUser['unionid'] !== $unionId)) {
            app('wechat.official_account')->customer_service->message(new Text('你的 CoDesign 账号已绑定其它微信，需更改绑定成当前微信后，才可收到消息通知。 更改方式：Web端进入 账号设置>账号绑定>微信绑定。'))->to($openId)->send();
            return false;
        }

        if (!$officialAccount) {
            Profile::query()->create([
                'user_id' => $user->id,
                'platform' => OfficialAccount::getName(),
                'platform_id' => $openId,
                'union_id' => $client->user->get($openId)['unionid'],
                'name' => \substr($openId, 0, 8),
            ]);

            User::saveUserExtends($user, ['scene' => $scene]);
        }

        if ('subscribe' === ($message['Event'] ?? null)) {
            User::saveUserExtends($user, [
                'subscribed_scene' => $scene,
                'subscribed_at' => \now(),
                'unsubscribed_at' => null
            ]);
        }

        // 推送绑定成功通知
        app('wechat.official_account')->customer_service->message(new Text('绑定 CoDesign 账号成功，后续团队消息审批等将第一时间通知你。'))->to($openId)->send();

        return true;
    }

    public function replayKeywords($message)
    {
        $content = Str::lower($message['Content']);

        if ('codesign' === $content || '2022' === $content) {
            app('wechat.official_account')->customer_service->message(new Text('<a href="https://support.weixin.qq.com/cgi-bin/mmsupport-bin/showredpacket?receiveuri=wmb0Ii8De1V&check_type=2#wechat_redirect">领取红包封面</a>'))->to($message['FromUserName'])->send();
        }
    }
}
