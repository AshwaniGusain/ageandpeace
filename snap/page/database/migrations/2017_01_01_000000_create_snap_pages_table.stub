<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('type');
            $table->string('uri');
            $table->string('slug');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->dateTime('publish_date')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->dateTime('unpublished_url')->nullable();
            $table->enum('status', ['published','unpublished','draft']);
            $table->integer('site_id')->unsigned()->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->integer('created_by_id')->unsigned()->nullable();
            $table->integer('updated_by_id')->unsigned()->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snap_pages');
    }
}
