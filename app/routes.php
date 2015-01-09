<?php

Route::get('/', array(
	'as'   => 'homePage',
	'uses' => 'HomeController@index'
));
Route::post('/more-photo', array(
	'as'   => 'morePhoto',
	'uses' => 'HomeController@more'
));

Route::any('/admin/login', array(
	'before' => 'isLogged',
	'as'   => 'loginPageAdmin',
	'uses' => 'AdminController@login'
));
Route::get('/admin', array(
	'before' => 'isAuth',
	'as'   => 'AdminPage',
	'uses' => 'AdminController@adminPage'
));
Route::get('/admin/addPage', array(
	'before' => 'isAuth',
	'as'   => 'GetDataAddPage',
	'uses' => 'AdminController@getDataAddPage'
));
Route::post('/admin/addPage', array(
	'as'   => 'SaveDataFromAddPage',
	'uses' => 'AdminController@saveDataAddPage'
));

/*Route::get('/admin/deletePage', array(
	'before' => 'isAuth',
	'as'   => 'deletePageAdmin',
	'uses' => 'AdminController@delete'
));
Route::post('/admin/deletePage', array(
	'as'   => 'postDeleteAlbumPhoto',
	'uses' => 'AdminController@deleteAlbumPhoto'
));*/
