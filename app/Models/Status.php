<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $fillable = ['title', 'order', 'board_id'];

    public $timestamps = true;

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
