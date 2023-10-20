<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;
    // use SoftDeletes;
    use CreatedUpdatedBy;

    protected $fillable = ['title', 'description', 'board_id'];

    public $timestamps = true;

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function tagTasks()
    {
        return $this->hasMany(TagTask::class);
    }
}
