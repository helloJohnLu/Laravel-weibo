<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];


    // 用户注册激活令牌，监听事件
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // 重置密码
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    // 模型关联 用户 & 微博 一对多
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    // 获取微博数据
    public function feed()
    {
        $user_ids = \Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,\Auth::user()->id);
        return Status::whereIn('user_id',$user_ids)
                    ->with('user')
                    ->orderBy('created_at','desc');
        // $user->followings == $user->followings()->get() // 等于 true
    }

    // 模型关联 用户 & 粉丝 多对多  获取粉丝关系列表
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers','user_id','follower_id');
    }

    // 模型关联 粉丝 & 用户 多对多 获取用户关注人列表
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    // 关注
    public function follow($user_ids)
    {
        // is_array 用于判断参数是否为数组，如果已经是数组，则没有必要再使用 compact 方法
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    // 取注关注
    public function unfollow($user_ids)
    {
        // is_array 用于判断参数是否为数组，如果已经是数组，则没有必要再使用 compact 方法
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    // 判断当前登录的用户 A 是否关注了用户 B
    public function isFollowing($user_ids)
    {
        return $this->followings->contains($user_ids);
    }
}
