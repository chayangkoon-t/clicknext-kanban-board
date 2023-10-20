<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $fillable = ['title', 'description', 'order', 'status_id', 'board_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function taskBoardUsers()
    {
        return $this->hasMany(TaskBoardUser::class);
    }

    public function tagTasks()
    {
        return $this->hasMany(TagTask::class);
    }
}
