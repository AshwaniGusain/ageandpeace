<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryProviderTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_provider_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('provider_type_id');
            $table->integer('precedence');
            $table->timestamps();

            $table->unique(array('category_id', 'provider_type_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_provider_type');
    }
}
