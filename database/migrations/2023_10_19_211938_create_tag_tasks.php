<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tag_id', false, true);
            $table->foreign('tag_id', 'tag_id_foreign')->references('id')->on('tags');
            $table->bigInteger('task_id', false, true);
            $table->foreign('task_id', 'task_id_foreign')->references('id')->on('tasks');
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
        Schema::dropIfExists('tag_tasks');
    }
}
