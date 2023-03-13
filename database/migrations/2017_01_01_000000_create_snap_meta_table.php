<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_id');
            $table->string('context');
            $table->string('name');
            $table->string('type', 50);
            $table->string('label');
            $table->longText('value')->nullable();
            $table->string('locale', 20);
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
        Schema::dropIfExists('snap_meta');
    }
}
