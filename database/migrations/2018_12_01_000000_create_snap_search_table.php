<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapSearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_search', function (Blueprint $table) {
            //$table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('uri');
            $table->string('category', 100)->nullable();
            $table->string('model', 50)->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->string('title');
            $table->longText('content');
            $table->longText('excerpt')->nullable();
            $table->timestamps();
            $table->unique('uri');
            $table->unique(['model', 'model_id']);
        });

        DB::statement('ALTER TABLE snap_search ADD FULLTEXT full(uri, title, content)');
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