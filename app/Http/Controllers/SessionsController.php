<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    // 渲染登录视图
    public function create()
    {
        return view('sessions.create');
    }

    // 处理登录提交
    public function store(Request $request)
    {
        // 校验数据合法性
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            // 该用户存在于数据库，且邮箱和密码相符合
            session()->flash('success','欢迎回来！');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            // 登录失败
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }
}
