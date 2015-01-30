<?php

class Photo extends Eloquent
{
	public static $unguarded = true;

	public static function get_all()
	{
		return Photo::all();
	}

	public static function get_count_photos_in_album($id_album)
	{
		return Photo::where('id_albom', '=', $id_album)->count();
	}

	public static function get_more_photos($id_album, $offset = 0, $limit = 9)
	{
		return Photo::select('id_photo', 'id_albom', 'title', 'description', 'reference_img')
			->where('id_albom', '=', $id_album)
			->skip($offset)
			->take($limit)
			->get();
	}

	public static function add($title, $description, $ref_img, $id_album)
	{
		try {
			return Photo::create([
				'title' => $title,
				'description' => $description,
				'reference_img' => $ref_img,
				'id_albom' => $id_album
			]);
		} catch (Exception $e) {
			return $e;
		}
	}

	public static function deleteFromAlbum($id_album)
	{
		return Photo::where('id_albom', '=', $id_album)->delete();
	}
	
	public static function edit_title_description($id_Photo, $new_title_photo, $new_description_photo = false)
	{
		if(!$new_description_photo) {
			return Photo::where('id_photo', '=', $id_Photo)->update(array('title' => $new_title_photo));
		}
		return Photo::where('id_photo', '=', $id_Photo)->update(array('title' => $new_title_photo, 'description' => $new_description_photo));
	}

	public static function deletePhoto($id_photo)
	{
		return Photo::where('id_photo', '=', $id_photo)->delete();
	}
}