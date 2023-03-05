<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kanelli\GraphValidateCode\Facades\GraphValidateCodeFacade;
use Kanelli\GraphValidateCode\GraphValidateCode;
use function PHPUnit\Framework\exactly;


class UserController extends Controller
{
    public function index($id)
    {
//        $user = Redis::get('user:profile:' . $id);

        $model = User::query()->where('name', '=', 'likan')->popular()->get();//使用本地作用域

        return User::chunk(200, function ($users) {
            foreach ($users as $user) {
                echo $user->name;
            }
        });

    }

    public function store(Request $request)
    {

//        $imageBase64 = GraphValidateCodeFacade::config(config('validate'))->getValidateImage('1234', 6666);
        $imageBase64 = app('gvc')->config(config('validate'))->getValidateImage('1234', '3309');
        $check = app('gvc')->config(config('validate'))->checkCode('1234', '3309');

        dd($check);
        var_dump($imageBase64);exit();


        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $user;
    }


    public function destory(Request $request)
    {
        return User::destroy($request->id);
    }


}
