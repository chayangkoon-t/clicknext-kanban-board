<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTask extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'task_id'];

    public $timestamps = true;

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
