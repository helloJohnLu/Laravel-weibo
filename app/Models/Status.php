<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // 可写入
    protected $fillable = ['content'];

    public function user()
    {
        // 模型关联 微博 & 用户 一对一
        return $this->belongsTo(User::class);
    }
}
