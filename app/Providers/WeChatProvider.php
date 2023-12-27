<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use EasyWeChat\OfficialAccount\Application as WeChatOfficialAccountApplication;
use EasyWeChat\MiniProgram\Application as WeChatMiniAppApplication;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class WeChatProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('wechat.official_account', function () {
            return $this->bindCache(new WeChatOfficialAccountApplication(
                array_merge(
                    $this->createEasyWeChatLogConfig('wechat.official_account'),
                    config('services.wechat.official_account', [])
                )
            ));
        });

        $this->app->bind('wechat.miniapp', function () {
            return $this->bindCache(new WeChatMiniAppApplication(
                array_merge(
                    $this->createEasyWeChatLogConfig('wechat.miniapp'),
                    config('services.wechat.miniapp', [])
                )
            ));
        });
    }

    /**
     * @param \EasyWeChat\Kernel\ServiceContainer $app
     *
     * @return mixed
     */
    public function bindCache($app)
    {
        $redisClient = new RedisAdapter(app('redis')->connection()->client());
        $app->rebind('cache', $redisClient);

        return $app;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function createEasyWeChatLogConfig(string $name)
    {
        $name = Str::slug($name);
        $path = \storage_path(\sprintf('/logs/easywechat-%s.log', $name));

        return [
            'http' => [
                'proxy' => \config('services.proxy'),
            ],
            'log' => [
                'default' => app()->isLocal() ? 'dev' : 'prod',
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => $path,
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => $path,
                        'level' => 'info',
                        'days' => 7,
                    ],
                ],
            ],
        ];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
