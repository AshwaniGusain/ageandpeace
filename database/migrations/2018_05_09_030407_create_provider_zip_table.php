<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderZipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_zip', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provider_id');
            $table->integer('zip_id');
            $table->timestamps();

            $table->unique(['provider_id', 'zip_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_zip');
    }
}
