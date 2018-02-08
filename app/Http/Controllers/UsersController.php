<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class UsersController extends Controller
{
    // 初始化构造函数
    public function __construct()
    {
        // 权限认证，过滤未登录用户的访问请求
        $this->middleware('auth',[
            'except' => ['show','create','store','index','confirmEmail']
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

        // 发送激活邮件
        $this->sendEmailConfirmationTo($user);

        // 闪存（保留到下个 HTTP 请求到来之前）注册成功提示信息
        session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收。');

        // 跳转
        return redirect('/');
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


    /**
     * 发送用户注册激活邮件
     *
     * @param $user
     */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'luj888@sina.com';
        $name = 'Admin';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    /**
     * 激活邮件
     *
     * @param $token    激活令牌
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，帐号激活成功！');
        return redirect()->route('users.show',[$user]);
    }
}
