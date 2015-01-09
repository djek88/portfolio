<?php

class Photo extends Eloquent
{
	public static $unguarded = true;

	public static function get_more_photos($album_id, $offset = 0, $limit = 9)
	{
		return Photo::where('id_albom', '=', $album_id)
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
}