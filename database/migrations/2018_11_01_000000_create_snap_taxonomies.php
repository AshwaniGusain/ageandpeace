<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapTaxonomies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug']);
        });

        Schema::create('snap_vocabularies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('handle');
            $table->tinyInteger('max_depth')->unsigned()->nullable();
            $table->boolean('active')->default('1');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['handle']);
        });

        Schema::create('snap_taxonomies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vocabulary_id')->unsigned();
            $table->integer('term_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->tinyInteger('depth')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['vocabulary_id', 'term_id', 'parent_id'], 'unique_vocabulary_term_parent');

            $table->foreign('vocabulary_id')->references('id')->on('snap_vocabularies')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('snap_terms')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('snap_taxonomies');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snap_search');
    }
}