<?php

Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');

Route::get('signup','UsersController@create')->name('signup');
Route::resource('users','UsersController');

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');


/* 重设密码 */
// 显示重置密码的邮箱发送页面
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 邮箱发送重设链接
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// 密码更新页面
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');

// 微博创建与删除
Route::resource('statuses','StatusesController',['only'=> ['store','destroy']]);

// 用户关注者列表和粉丝列表
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

// 关注表单
Route::post('/users/followers/{user}','FollowersController@store')->name('followers.store');
Route::delete('/users/followers/{user}','FollowersController@destroy')->name('followers.destroy');
