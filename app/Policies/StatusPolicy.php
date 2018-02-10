<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 删除微博的权限策略
    public function destroy(User $user, Status $status)
    {
        // 当前用户的 id 与要删除的微博作者 id 相同时，验证才能通过
        return $user->id === $status->user_id;
    }
}
