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
	'before'	=> 'isLogged',
	'as'		=> 'loginPageAdmin',
	'uses'		=> 'AdminController@login'
));
Route::get('/admin', array(
	'before'	=> 'isAuth',
	'as'		=> 'AdminPage',
	'uses'		=> 'AdminController@adminPage'
));
Route::get('/admin/addPage', array(
	'before'	=> 'isAuth',
	'as'		=> 'GetDataAddPage',
	'uses'		=> 'AdminController@getDataAddPage'
));
Route::post('/admin/addPage', array(
	'as'   => 'SaveDataFromAddPage',
	'uses' => 'AdminController@saveDataAddPage'
));

Route::post('/admin/deletePage/getAlbum', array(
	'before'	=> 'isAuth',
	'as'		=> 'GetAlbumDeletePage',
	'uses'		=> 'AdminController@getAlbumDeletePage'
));
Route::post('/admin/deletePage/getPhoto', array(
	'as'   => 'GetPhotoDeletePage',
	'uses' => 'AdminController@getPhotoDeletePage'
));
Route::post('/admin/deletePage/deleteAlbum', array(
	'as'   => 'DeleteAlbumDeletePage',
	'uses' => 'AdminController@deleteAlbumDeletePage'
));
Route::post('/admin/deletePage/deletePhoto', array(
	'as'   => 'DeletePhotoDeletePage',
	'uses' => 'AdminController@deletePhotoDeletePage'
));

/*Route::post('/admin/deletePage/more', array(
	'as'   => 'MorePhotosDeletePage',
	'uses' => 'AdminController@morePhotosDeletePage'
));*/
