<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskBoardUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_board_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('board_user_id', false, true);
            $table->foreign('board_user_id', 'task_user_board_user_id_foreign')->references('id')->on('board_users');
            $table->bigInteger('task_id', false, true);
            $table->foreign('task_id', 'task_user_task_id_foreign')->references('id')->on('tasks');
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
        Schema::dropIfExists('task_board_users');
    }
}
