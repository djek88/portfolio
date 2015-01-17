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

Route::post('/admin/deletePage/getAlbum', array(
	'before' => 'isAuth',
	'as'   => 'GetAlbumDeletePage',
	'uses' => 'AdminController@getAlbumDeletePage'
));
/*Route::post('/admin/deletePage', array(
	'as'   => 'DeleteDataDeletePage',
	'uses' => 'AdminController@deleteDataDeletePage'
));
Route::post('/admin/deletePage/more', array(
	'as'   => 'MorePhotosDeletePage',
	'uses' => 'AdminController@morePhotosDeletePage'
));*/
