<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            //$table->unsignedInteger('category_id')->nullable();
            $table->integer('provider_type_group_id')->unsigned()->nullable();
            $table->integer('precedence')->default(0);
            $table->boolean('active')->default(1);

            $table->unique('slug');

            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('provider_type_group_id')->references('id')->on('provider_type_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_types');
    }
}
