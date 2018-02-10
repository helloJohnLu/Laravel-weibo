<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    // 权限过滤
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 发布微博
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
