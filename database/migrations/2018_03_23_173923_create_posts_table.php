<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
	        //$table->integer('provider_author_id')->unsigned()->nullable();
	        $table->integer('category_id')->unsigned()->nullable();
//	        $table->integer('hero_image_id')->unsigned();
	        $table->string('title', 255);
            $table->string('slug', 255);
	        $table->longText('body');
            $table->text('excerpt')->nullable();
	        $table->enum('status', ['draft', 'published', 'unpublished'])->default('draft');
            $table->dateTime('publish_date')->nullable();
            $table->integer('author_id')->unsigned()->nullable();
            $table->integer('precedence')->default('0')->nullable();
            $table->boolean('featured')->default('0')->comment('Checking this box will make it available to the homepage.');
            $table->timestamps();
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
        Schema::dropIfExists('posts');
    }
}
