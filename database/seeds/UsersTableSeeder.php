<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password','remember_token'])->toArray());

        // 把第一个用户设置成为管理员
        $user = User::find(1);
        $user->name = 'jack';
        $user->email = 'luj888@sina.com';
        $user->is_admin = true;
        $user->activated = true;
        $user->save();
    }
}
