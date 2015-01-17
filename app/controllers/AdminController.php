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

	private function getDataAboutAlbums()
	{
		$albums_id_name = Albom::select_id_name_albom(); // array Album['id', 'name'] массив всех альбомов
		$albums_id_count = Photo::select(DB::raw(' id_albom as id, count(id_photo) as count'))
										->groupBy('id_albom')->get(); // array Photo['id', 'count'] массив альбомов в которых есть фотографии 
		$albums_id_count_name = array();
		$albums_with_photos = array();

		for ($i=0; $i < count($albums_id_name); $i++) {
			$exist = false;
			for ($j=0; $j < count($albums_id_count); $j++) {
				if($albums_id_name[$i]['id'] == $albums_id_count[$j]['id']) {
					$albums_with_photos[] = $albums_id_count_name[] = ['id' => $albums_id_name[$i]['id'], 'count' => $albums_id_count[$j]['count'], 'name' => $albums_id_name[$i]['name']];
					$exist = true;
					break;
				}
			}
			if(!$exist) {
				$albums_id_count_name[] = ['id' => $albums_id_name[$i]['id'], 'count' => 0, 'name' => $albums_id_name[$i]['name']];
			}
		}

		return ['all_albums' => $albums_id_count_name,
				'albums_with_photos' => $albums_with_photos];
	}
	
	public function morePhotosDeletePage()
	{
		return "moreeeeeeeeeeeeee";
	}

	public function getDataDeletePage()
	{
		$pageData = array();
		$dataAboutAlbums = $this->getDataAboutAlbums();

		$pageData['all_albums']	= $dataAboutAlbums['all_albums'];
		$pageData['albums_with_photos'] = $dataAboutAlbums['albums_with_photos'];
		$pageData['all_photos'] = array();

		for($i=0; $i<count($pageData['albums_with_photos']); $i++) {
			$Photos = Photo::get_more_photos($pageData['albums_with_photos'][$i]['id'], 0, 3);
			foreach ($Photos as $photo) {
				$pageData['all_photos'][] = $photo;
			}
		}

		return $pageData;
	}



	public function deleteDataDeletePage()
	{
		if(Input::has('id_album')) {
			$id_album = Input::get('id_album');

			$photos_ref = Photo::select('reference_img')->where('id_albom', '=', $id_album)->get();
			$affectedRowsPhotos = Photo::deleteFromAlbum($id_album);
			if($affectedRowsPhotos) {
				for ($i=0; $i<count($photos_ref); $i++) { 
					if(File::exists($photos_ref[$i]['reference_img'])) {
						File::delete($photos_ref[$i]['reference_img']);
					}
				}
			}
			$affectedRowsAlbums = Albom::deleteAlbum($id_album);
			$dataAboutAlbums = $this->getDataAboutAlbums();
			
			return ['isDeleteAlbum' 	 => $affectedRowsAlbums,
					'deletedPhotos' 	 => $affectedRowsPhotos,
					'all_albums'		 => $dataAboutAlbums['all_albums'],
					'albums_with_photos' => $dataAboutAlbums['albums_with_photos']
			];
		}
	}

	public function edit()
	{
		return "В разработке...";
	}
}