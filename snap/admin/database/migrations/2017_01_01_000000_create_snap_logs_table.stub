<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class TaxonomiesTable
 */
class CreateSnapLogsTable extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('snap_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->string('level', 30);
			$table->text('message');
			$table->longText('data');
			$table->integer('user_id')->unsigned();
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
		Schema::dropIfExists('snap_logs');
	}
}