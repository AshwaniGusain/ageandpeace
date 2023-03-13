<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitchenSinkTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kitchensink', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->date('publish_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->time('time')->nullable();
            $table->integer('range')->nullable();
            $table->string('timezone')->nullable();
            $table->longText('textarea')->nullable();;
            $table->longText('wysiwyg')->nullable();;
            $table->float('float', 8, 2)->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->integer('number')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->string('password')->nullable();
            $table->string('select')->nullable();
            $table->string('select2')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->point('coordinates')->nullable();
            $table->string('phone')->nullable();
            $table->string('radios')->nullable();
            $table->string('color')->nullable();
            $table->longText('repeatable')->nullable();
            $table->boolean('checkbox')->default(0);
            $table->boolean('active')->default(1);
            $table->enum('status', ['published', 'draft', 'unpublished'])->default('draft');
            $table->integer('precedence')->default(0);
            $table->integer('dependent')->nullable()->unsigned();
            $table->string('toggle')->nullable();
            $table->longText('keyval')->nullable();
            $table->unsignedInteger('updated_by_id')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('kitchensink');
    }
}
