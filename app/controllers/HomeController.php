<?php

class HomeController extends BaseController {

	public function index()
	{
		$albums_name = Albom::select_id_name_albom();
		$all_albums = Photo::select(DB::raw(' id_albom as id, count(id_photo) as count'))->groupBy('id_albom')->get();
		$all_photos = array();

		for($i = 0; $i < count($all_albums); $i++){
			for($j = 0; $j < count($albums_name); $j++){//дополняет имена
				if($all_albums[$i]['id'] == $albums_name[$j]['id']) {
					$all_albums[$i]['name'] = $albums_name[$j]['name'];
					break;
				}
			}

			// Вытаскиваем из каждого альбома по 3 фотографии
			if($all_albums[$i]['count'] > 0) {
				foreach (Photo::get_more_photos($all_albums[$i]['id'], 0, 3) as $photo) {
					$all_photos[] = $photo;
				}
			}
		}

		// $all_albums массив альбомов ['id', count, name]
		// $all_photos массив фотографий
		return View::make('home', array('alboms' => $all_albums,
										'photos' => $all_photos));
	}
	
	public function more()
	{
		$input = Input::all();
		$offset = isset($input['offset']) ? (int)$input['offset'] : 0;
		$albom_id = isset($input['aid']) ? (int)$input['aid'] : 0;
		$limit = isset($input['limit']) ? (int)$input['limit'] : 0;
		if($limit > 30)
			$limit = 30;
		else if($limit < 0)
			$limit = 1;

		$result = array();
		foreach (Photo::get_more_photos($albom_id, $offset, $limit) as $photo) {
			$result[] = array(
				'id' => $photo['id_photo'],
				'title' => $photo['title'],
				'desc' => $photo['description'],
				'ref_img' => $photo['reference_img'],
				'id_albom' => $photo['id_albom'],
			);
		}

		return $result;
	}
}
