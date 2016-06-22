<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScantasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('scan_profile_id');
            $table->string('target');
            $table->char('progress',20);
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
        Schema::drop('scan_tasks');
    }
}
