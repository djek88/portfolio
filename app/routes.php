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

Route::post('/admin/editPage/getAlbum', array(
	'before'	=> 'isAuth',
	'as'		=> 'GetAlbumEditPage',
	'uses'		=> 'AdminController@getAlbumEditPage'
));
Route::post('/admin/editPage/getPhoto', array(
	'as'   => 'GetPhotoEditPage',
	'uses' => 'AdminController@getPhotoEditPage'
));
Route::post('/admin/editPage/editAlbum', array(
	'as'   => 'EditAlbumEditPage',
	'uses' => 'AdminController@editAlbumEditPage'
));
Route::post('/admin/editPage/deleteAlbum', array(
	'as'   => 'DeleteAlbumDeletePage',
	'uses' => 'AdminController@deleteAlbumEditPage'
));
Route::post('/admin/editPage/editPhoto', array(
	'as'   => 'EditPhotoEditPage',
	'uses' => 'AdminController@editPhotoEditPage'
));
Route::post('/admin/editPage/deletePhoto', array(
	'as'   => 'DeletePhotoDeletePage',
	'uses' => 'AdminController@deletePhotoEditPage'
));
