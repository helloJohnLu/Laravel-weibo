<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    public function __construct()
    {
        // 只让未登录用户访问登录页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

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

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // 该用户存在于数据库，且邮箱和密码相符合
            if (Auth::user()->activated) {
                session()->flash('success','欢迎回来！');
                return redirect()->intended(route('users.show',[Auth::user()]));
            }else{
                Auth::logout();
                session()->flash('warning','你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        }else{
            // 登录失败
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    // 退出登录
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已退出登录');
        return redirect('login');
    }
}
