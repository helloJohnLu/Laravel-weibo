<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    // 权限过滤
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 发布微博
    public function store(Request $request)
    {
        // 校验数据合法性
        $this->validate($request,[
            'content'       =>  'required|max:140'
        ]);

        // 逻辑
        Auth::user()->statuses()->create([
            'content'       =>  \request('content'),
        ]);

        return redirect()->back();
    }
}
