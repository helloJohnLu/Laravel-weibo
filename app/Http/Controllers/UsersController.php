<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // 初始化构造函数
    public function __construct()
    {
        // 权限认证，过滤未登录用户的访问请求
        $this->middleware('auth',[
            'except' => ['show','create','store','index']
        ]);

        // 只让未登录用户访问注册页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 校验数据合法性
        $this->validate($request,[
            'name' => 'required|max:50|min:3',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        // 数据入库
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);    // 自动登录

        // 闪存（保留到下个 HTTP 请求到来之前）注册成功提示信息
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');

        // 跳转
        return redirect()->route('users.show',[$user]);
        // 以上代码等同于：redirect()->route('users.show', [$user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->gravatar();
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update',$user);    // 权限认证
        return view('users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request)
    {
        // 校验数据合法性
        $this->validate($request,[
            'name' => 'required|max:50|min:3',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update',$user);    // 权限认证

        // 更新数据库
        $data = [];
        $data['name'] = $request->name;

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        // 更新成功提示信息
        session()->flash('success','个人资料更新成功！');

        return redirect()->route('users.update', $user->id);

    }

    /**
     * 删除用户
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }
}
