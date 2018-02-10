<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function home()
    {
        $feed_items = [];   // 保存微博动态数据

        // 获取微博数据
        if (\Auth::check()) {   // 检查用户是否已登录
            $feed_items = \Auth::user()->feed()->paginate(20);
        }

        return view('static_pages/home', compact('feed_items'));
    }


    public function help()
    {
        return view('static_pages/help');
    }


    public function about()
    {
        return view('static_pages/about');
    }
}
