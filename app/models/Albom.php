<?php

class Albom extends Eloquent
{
	public static $unguarded = true;
	
	public static function get_all()
	{
		return Albom::all();
	}

	public static function select_id_name_albom()
	{
		return Albom::select('id', 'name')->get();
	}

	public static function add($name_album)
	{
		try {
			return Albom::create([
				'name' => $name_album
			]);
		} catch (Exception $e) {
			return $e;
		}
	}

	public static function deleteAlbum($id_album)
	{
		return Albom::where('id', '=', $id_album)->delete();
	}

	public static function getAlbumById($id_album)
	{
		return Albom::where('id', '=', $id_album)->get();
	}
}