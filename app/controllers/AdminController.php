<?php

class AdminController extends BaseController {

	public function login()
	{
		if(Input::has('login') && Input::has('password')) {
			if(Input::get('login') == 'admin' && Input::get('password') == 'admin') {
				Session::put('has_auth', 1);
				return Redirect::route('AdminPage');
			}
		}
		return View::make('/loginAdmin');
	}

	public function adminPage()
	{
		return View::make('/adminPage');
	}







	public function getDataAddPage()
	{
		$all_albums = Albom::select_id_name_albom();
		return $all_albums;
	}

	public function saveDataAddPage()
	{
		if(Input::has('name_album')) {
			$message = null;
			$name_album = trim(Input::get('name_album'));
			$valid = Validator::make(['name_album' => $name_album],
									 ['name_album' => ['required',
									 					'min:5',
									 					'max:30',
									 					'alpha_dash',
									 					'regex:/^[a-zA-Z]+[a-zA-Z1-9]*$/',
									 					'unique:alboms,name']
			]);
			if($valid->fails()) {
				$message = "Имя альбома не корректное! Возможно альбом с таким именем уже существует.";
			} else {
				$result = Albom::add($name_album);
				if($result instanceof Exception) {
					$message = 'Неизвестная ошибка при создании!';
				} else {
					$message = 'Альбом создан.';
				}
			}
			$all_albums = Albom::select_id_name_albom();
			return array(
				'message'    => $message,
				'all_albums' => $all_albums
			);
		} else if(Input::file() && Input::hasFile('my-file')) {
			$title = Input::get('title');
			$desc = Input::get('description');
			$album_id = Input::get('album');

			$valid = Validator::make(
				['title'    => $title,
				 'desc'     => $desc,
				 'album_id' => $album_id],
				['title'    => ['regex:/^([a-zA-Z1-9]{5,30})$/'],
				 'desc' => ['required', 'regex:/^([a-zA-Z1-9 ]{10,100})$/'],
				 'album_id' => 'required|integer']
			);

			$file = Input::file('my-file');
			$new_file_name = microtime(true).".".Input::file('my-file')->getClientOriginalExtension();
			$path_to_img = "_include/img/work/";

			try {
				if($valid->fails()) {
					throw new Exception("Data not valid!");
				} else {
					$ExistAlbum = isset(Albom::getAlbumById($album_id)[0]);
					if(!$ExistAlbum) {
						throw new Exception("Required album is not exist.");
					}

					$file->move($path_to_img, $new_file_name);

					$response = Photo::add($title, $desc, $path_to_img.$new_file_name, $album_id);
					if($response instanceof Exception) {
						throw new Exception("Database error!");
					}
					return ['message' => "File success add!"];
				}
			} catch(Exception $e) {
				if(File::exists($path_to_img.$new_file_name)) {
					File::delete($path_to_img.$new_file_name);
				}
				return ['message' => $e->getMessage(),
				'error' => true];
			}
		}
	}







	private function getPhotosFromAlbum($id_album, $offset, $limit)
	{
		$name_album = Albom::get_name_album($id_album);
		$data = Photo::get_more_photos($id_album, $offset, $limit);
		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['name_album'] = $name_album;
		}
		return $data;
	}

	public function getAlbumEditPage()
	{
		$offset_album = Input::has('offset_album') ? (int)Input::get('offset_album') : 0;
		$amount_album = Input::has('amount_album') ? (int)Input::get('amount_album') : 0;
		$amount_photo_in_album = Input::has('amount_photo_in_album') ? (int)Input::get('amount_photo_in_album') : 0;
		
		$pageData['amount_albums'] = Albom::count();		
		$pageData['albums'] = array();
		
		if($offset_album >= 0 && $amount_album > 0) {
			if($amount_album > 20)
				$amount_album = 20;

			$pageData['albums'] =  Albom::get_more_albums($offset_album, $amount_album);
			for($i=0; $i<count($pageData['albums']); $i++) {
				$pageData['albums'][$i]['amount_photos'] = Photo::get_count_photos_in_album($pageData['albums'][$i]['id']);
				$pageData['albums'][$i]['photos'] = array();
				if($amount_photo_in_album > 0) {
					$pageData['albums'][$i]['photos'] = $this->getPhotosFromAlbum(
						$pageData['albums'][$i]['id'],
						0,
						$amount_photo_in_album
					);
				}
			}
		}
		return $pageData;
	}

	public function getPhotoEditPage()
	{
		$id_album = Input::has('id_album') ? (int)Input::get('id_album') : -1;
		$offset = Input::has('offset') ? (int)Input::get('offset') : 0;
		$amount_photo = Input::has('amount_photo') ? (int)Input::get('amount_photo') : 0;

		if($id_album >= 0 && $offset >= 0 && $amount_photo > 0) {
			if($amount_photo > 30) {
				$amount_photo = 30;
			}
			return $this->getPhotosFromAlbum($id_album, $offset, $amount_photo);
		}
	}

	public function editAlbumEditPage()
	{
		if(Input::has('id_album') && Input::has('name_album')) {
			$id_album = (int)Input::get('id_album');			
			$name_album = trim(Input::get('name_album'));

			$valid = Validator::make(['name_album' => $name_album],
									 ['name_album' => ['required',
									 					'min:5',
									 					'max:30',
									 					'alpha_dash',
									 					'regex:/^[a-zA-Z]+[a-zA-Z1-9]*$/',
									 					'unique:alboms,name']
			]);
			if(!$valid->fails() && $id_album > 0) {
				return Albom::edit_name_album($id_album, $name_album);
			}
		}
	}

	public function deleteAlbumEditPage()
	{
		$id_album = Input::has('id_album') ? (int)Input::get('id_album') : -1;

		if($id_album >= 0) {
			$affectedRowsAlbums = Albom::deleteAlbum($id_album);
			$affectedRowsPhotos = 0;

			if($affectedRowsAlbums) {
				$photos_ref = Photo::select('reference_img')->where('id_albom', '=', $id_album)->get();
				$affectedRowsPhotos = Photo::deleteFromAlbum($id_album);
				
				if($affectedRowsPhotos) {
					for ($i=0; $i<count($photos_ref); $i++) { 
						if(File::exists($photos_ref[$i]['reference_img'])) {
							File::delete($photos_ref[$i]['reference_img']);
						}
					}
				}

			}
			return [
				'DeletedAlbum'  => $affectedRowsAlbums,
				'deletedPhotos' => $affectedRowsPhotos,
			];
		}
	}

	public function deletePhotoEditPage()
	{
		$id_photo = Input::has('id_photo') ? (int)Input::get('id_photo') : -1;

		if($id_photo >= 0) {
			$photos_ref = Photo::select('reference_img')->where('id_photo', '=', $id_photo)->get();
			$affectedRowsPhotos = Photo::deletePhoto($id_photo);

			if($affectedRowsPhotos) {
				for ($i=0; $i<count($photos_ref); $i++) { 
					if(File::exists($photos_ref[$i]['reference_img'])) {
						File::delete($photos_ref[$i]['reference_img']);
					}
				}
				return 1;
			}
		}
	}
}