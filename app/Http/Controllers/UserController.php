<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Kanelli\GraphValidateCode\Facades\GraphValidateCodeFacade;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * 获取用户信息
     *
     * @param int $id
     * @return JsonResponse
     */
    public function index(int $id): JsonResponse
    {
        // 清理注释代码：$user = Redis::get('user:profile:' . $id);
        
        // 使用本地作用域查询（需要在User模型中定义popular scope）
        $users = User::query()
            ->where('name', '=', 'likan')
            ->when(method_exists(User::class, 'scopePopular'), function ($query) {
                return $query->popular();
            })
            ->get();

        // 优化chunk处理，返回JSON响应而不是直接echo
        $userNames = [];
        User::chunk(200, function ($users) use (&$userNames) {
            foreach ($users as $user) {
                $userNames[] = $user->name;
            }
        });

        return response()->json([
            'users' => $users,
            'user_names' => $userNames
        ]);
    }

    /**
     * 创建新用户
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        // 验证码功能（开发环境可用）
        if (app()->environment('local', 'development')) {
            $imageBase64 = app('gvc')->config(config('validate'))->getValidateImage('1234', '3309');
            $check = app('gvc')->config(config('validate'))->checkCode('1234', '3309');
            
            // 移除调试代码：dd($check); var_dump($imageBase64);exit();
            // 可以记录到日志：\Log::info('Validation check result', ['check' => $check]);
        }

        // 增强输入验证
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // 添加密码确认
            'avatar' => 'nullable|url|max:500'
        ]);

        // 创建用户（密码会通过User模型的setPasswordAttribute自动加密）
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'avatar' => $validatedData['avatar'] ?? null,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->makeHidden(['password'])
        ], 201);
    }

    /**
     * 删除用户（修复方法名拼写错误：destory -> destroy）
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroy(Request $request): JsonResponse
    {
        // 添加输入验证
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:users,id'
        ]);

        $deleted = User::destroy($validatedData['id']);

        return response()->json([
            'message' => $deleted > 0 ? 'User deleted successfully' : 'User not found',
            'success' => $deleted > 0
        ]);
    }

    /**
     * 导出用户数据
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        // 注意：需要确保UsersExport类存在
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
