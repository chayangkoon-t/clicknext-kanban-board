<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskBoardUser extends Model
{
    use HasFactory;

    protected $fillable = ['board_user_id', 'task_id'];

    public $timestamps = false;

    public function boardUser()
    {
        return $this->belongsTo(BoardUser::class);
    }

    public function task()
    {
        return $this->belongsTo(Board::class);
    }
}
