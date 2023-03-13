<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('membership_type_id')->unsigned()->nullable();
            $table->integer('provider_type_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug', 255);
            $table->string('street', 100);
            $table->string('city', 50);
            $table->string('state', 2);
            $table->string('zip', 20);
            $table->string('phone', 50);
            $table->string('website', 255)->nullable();
            $table->text('description')->nullable();
            $table->date('expiration_date')->nullable();
            $table->point('geo_point')->nullable();
            $table->boolean('national')->default(false);
            $table->boolean('active')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('providers');
    }
}
