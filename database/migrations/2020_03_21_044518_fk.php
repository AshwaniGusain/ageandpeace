<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('memberships', function ($table) {
            $table->foreign('membership_type_id')->references('id')->on('membership_types');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });

        Schema::table('ratings', function ($table) {
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });

        Schema::table('providers', function ($table) {
            $table->foreign('user_id')->references('id')->on('snap_users');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('membership_type_id')->references('id')->on('membership_types');
            $table->foreign('provider_type_id')->references('id')->on('provider_types');
        });

        Schema::table('customers', function ($table) {
            $table->foreign('user_id')->references('id')->on('snap_users');
        });

        Schema::table('posts', function ($table) {
            //$table->foreign('provider_author_id')->references('id')->on('providers')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('author_id')->references('id')->on('snap_users')->onDelete('set null');
            //$table->foreign('hero_image_id)->references('id')->on('images');

        });

        Schema::table('tasks', function ($table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::table('customer_tasks', function ($table) {
            //$table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('task_id')->references('id')->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
