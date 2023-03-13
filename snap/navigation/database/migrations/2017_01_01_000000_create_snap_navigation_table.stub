<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapNavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snap_navigation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link')->comment('The link location associated with the menu item (does not need to include http if it is local');
            $table->string('key')->comment('The unique identifier to relate to this navigation item');
            $table->string('label')->nullable()->comment('The menu label to display for the menu item');
            $table->integer('parent_id')->unsigned()->nullable()->comment('The parent menu item this menu item exists under (if any)');
            $table->integer('group_id')->unsigned()->default(1)->comment('The menu group to associate this menu item to');
            $table->integer('precedence')->unsigned()->nullable()->comment('The order n which to display the menu item (lower numbers are higher in the list');
            $table->string('attributes')->nullable()->comment('HTML attributes to associate with the menu item like class, target etc');
            $table->string('selected')->nullable();
            $table->string('language')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->integer('created_by_id')->unsigned()->nullable();
            $table->integer('updated_by_id')->unsigned()->nullable();
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
        Schema::dropIfExists('snap_navigation');
    }
}
