<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeWorkController extends Controller
{
    public function getJssdkConfig(Request $request)
    {
        $APIs = $request->query('apis', []);
        $debug = $request->has('debug');

        return \response()->json(\app('wework.tencent')->jssdk->buildConfig($APIs, $debug));
    }
}
