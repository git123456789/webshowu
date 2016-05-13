<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
//根目录
Route::group(['as' => 'front::'],function(){
	//目录网站
	Route::get('/', 'indexController@index');
	Route::get('/ceshi', 'indexController@ceshi');
	Route::get('/tags/{str}', 'indexController@tags');
	Route::get('/diypage-{id}.html','indexController@diypage');
	Route::get('/footmark-{id}.html','Footmark@info');

    Route::get('/webdir', 'Webdir@lists');
	Route::get('/webdir/{id}','Webdir@lists');
	Route::get('/siteinfo-{id}.html','Webdir@info');

	Route::get('/article', 'Article@index');
	Route::get('/article/{id}','Article@lists');
	Route::get('/artinfo-{id}.html','Article@info');
	
	//seo
	//route::get('/sitemap.xml','indexController@get_sitemap');
	
});

//注册-登陆-找回密码
Route::group(['middleware' => 'login'], function() {
	//注册和登陆
    Route::get('/login', 'Users@get_login');
    Route::post('/login', 'Users@post_login');

	Route::get('/register', 'Users@get_register');
	Route::post('/register', 'Users@post_register');
});
//个人中心
Route::group(['middleware' => 'auth', 'as' => 'home::'], function() {
    Route::get('/home', 'Home@index');
	Route::get('/logout', 'Home@logout');
	Route::post('/home/ajaxget/{type}', 'Home@ajaxget');

	Route::get('/site', 'Home@get_site');
	Route::get('/site/add', 'Home@get_site_add');
	Route::post('/site/add', 'Home@post_site_add');
	Route::get('/site/edit/{id}', 'Home@get_site_edit');
	Route::post('/site/edit/{id}', 'Home@post_site_edit');

	Route::get('/art', 'Home@get_art');
	Route::get('/art/add', 'Home@get_art_add');
	Route::post('/art/add', 'Home@post_art_add');
	Route::get('/art/edit/{id}', 'Home@get_art_edit');
	Route::post('/art/edit/{id}', 'Home@post_art_edit');

	Route::get('/profile', 'Home@get_profile');
	Route::post('/profile', 'Home@get_profile');

	Route::get('/editpwd', 'Home@get_editpwd');
	Route::post('/editpwd', 'Home@get_editpwd');

	//admin
	Route::get('/admin/pagelist', 'Admin@get_pagelist');
	Route::get('/admin/pagelist/edit/{id}', 'Admin@get_pagelist_edit');

	Route::get('/admin/article', 'Admin@get_article');
	Route::get('/admin/article/edit/{id}', 'Admin@get_article_edit');
	Route::post('/admin/article/edit/{id}', 'Admin@post_article_edit');

	Route::get('/admin/website','Admin@get_website');
	Route::get('/admin/website/edit/{id}','Admin@get_website_edit');
	Route::post('/admin/website/edit/{id}','Admin@post_website_edit');
});