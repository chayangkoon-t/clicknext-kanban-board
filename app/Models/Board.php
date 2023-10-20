<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $fillable = ['title', 'description', 'order', 'user_id', 'status_id'];

    public $timestamps = true;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class)->orderBy('order');
    }

    public function boardUsers()
    {
        return $this->hasMany(BoardUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function boardUsers()
    // {
    //     return $this->hasMany(Status::class)->orderBy('order');
    // }

    protected static function booted()
    {
        static::created(function ($board) {
            // Create default statuses
            $board->statuses()->createMany([
                [
                    'title' => 'Backlog',
                    'order' => 1
                ],
                [
                    'title' => 'Upcoming',
                    'order' => 2
                ],
                [
                    'title' => 'In Progress',
                    'order' => 3
                ],
                [
                    'title' => 'Done',
                    'order' => 4
                ]
            ]);
        });

        static::created(function ($board) {
            // Create default statuses
            $board->boardUsers()->createMany([
                [
                    'user_id' => auth()->user()->id,
                    'board_id' => $board->id,
                    'active' => 1,
                ],
            ]);
        });
    }
}
