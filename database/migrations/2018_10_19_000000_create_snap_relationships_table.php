<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('key');
            $table->string('table');
            $table->integer('foreign_key');
            $table->string('foreign_table');
            $table->string('context')->default('');
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
        Schema::dropIfExists('snap_relationships');
    }
}
