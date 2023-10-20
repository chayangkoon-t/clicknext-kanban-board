<?php

namespace App\Models;

use App\Models\Board;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardUser extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $fillable = ['user_id', 'board_id', 'active'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function taksBoardUsers()
    {
        return $this->hasMany(TaskBoardUser::class);
    }
}
