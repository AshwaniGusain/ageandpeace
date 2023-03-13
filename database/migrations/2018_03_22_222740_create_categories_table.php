<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('precedence')->default(0);
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->unique('slug');
            $table->foreign('parent_id')->references('id')->on('categories');
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
        Schema::dropIfExists('categories');
    }
}
