<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_id')->unsigned();
            $table->string('entity');
            $table->longText('data');
            $table->smallInteger('version');
            $table->timestamps();
            $table->integer('created_by_id')->unsigned()->nullable();
            $table->integer('updated_by_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snap_archives');
    }
}
