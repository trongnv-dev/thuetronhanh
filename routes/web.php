<?php

Route::group(['namespace' => 'Front'], function () {
    Route::get('/', 'IndexController@index')->name('index');
    Route::get('category/{id}','CategoryController@getMotelByCategoryId')->name('category');
    Route::get('/phongtro/{slug}', 'MotelRoomController@getMotelBySlug')->name('model');
    Route::get('/report/{id}','MotelController@userReport')->name('user.report');
    Route::get('/motelroom/del/{id}','MotelController@user_del_motel');
    Route::post('/searchmotel','MotelController@searchMotelAjax');
});

/* User */
Route::group(['prefix'=>'user'], function () {
    Route::get('register','UserController@get_register');
    Route::post('register','UserController@post_register')->name('user.register');

    Route::get('login','UserController@get_login');
    Route::post('login','UserController@post_login')->name('user.login');
    Route::get('logout','UserController@logout');

    Route::get('dangtin','UserController@get_dangtin')->middleware('dangtinmiddleware');
    Route::post('dangtin','UserController@post_dangtin')->name('user.dangtin')->middleware('dangtinmiddleware');

    Route::get('profile','UserController@getprofile')->middleware('dangtinmiddleware');
    Route::get('profile/edit','UserController@getEditprofile')->middleware('dangtinmiddleware');
    Route::post('profile/edit','UserController@postEditprofile')->name('user.edit')->middleware('dangtinmiddleware');
});
/* ----*/

/* Admin */
Route::get('admin/login','Admin\AdminController@getLogin');
Route::post('admin/login','Admin\AdminController@postLogin')->name('admin.login');
Route::group(['prefix'=>'admin', 'middleware'=>'adminmiddleware', 'namespace' => 'Admin'], function () {
    Route::get('logout','AdminController@logout');
    Route::get('','AdminController@getIndex');
    Route::get('thongke','AdminController@getThongke');
    Route::get('report','AdminController@getReport');
    Route::group(['prefix'=>'users'],function(){
        Route::get('list','AdminController@getListUser');
        Route::get('edit/{id}','AdminController@getUpdateUser');
        Route::post('edit/{id}','AdminController@postUpdateUser')->name('admin.user.edit');
        Route::get('del/{id}','AdminController@DeleteUser');
    });
    Route::group(['prefix'=>'motelrooms'],function(){
        Route::get('list','AdminController@getListMotel');
        Route::get('approve/{id}','AdminController@ApproveMotelroom');
        Route::get('unapprove/{id}','AdminController@UnApproveMotelroom');
        Route::get('del/{id}','AdminController@DelMotelroom');
    });
});
/* End Admin */
