<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_tasks', function (Blueprint $table) {
            $table->increments('id');
            //$table->string('title');
            //$table->text('description');
            $table->enum('priority', ['1', '2', '3', '4', '5'])->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            //$table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('task_id')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('completed')->default(false);
            $table->date('completed_date')->nullable();
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
        Schema::dropIfExists('user_tasks');
    }
}
