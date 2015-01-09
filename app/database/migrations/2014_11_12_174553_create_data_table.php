<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alboms', function($table)
		{
			$table->increments('id');
			$table->string('name', 100)->unique();
			$table->timestamps();
		});

		Schema::create('photos', function($table)
		{
			$table->increments('id_photo');
			$table->string('id_albom', 100);
			$table->string('title', 100);
			$table->text('description');
			$table->string('reference_img');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('alboms');
		Schema::drop('photos');
	}
}