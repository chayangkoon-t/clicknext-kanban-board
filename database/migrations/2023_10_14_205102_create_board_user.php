<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id', false, true);
            $table->foreign('user_id', 'board_user_user_id_foreign')->references('id')->on('users');
            $table->bigInteger('board_id', false, true);
            $table->foreign('board_id', 'board_user_board_id_foreign')->references('id')->on('boards');
            $table->boolean('active')->default(false);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_user');
    }
}
