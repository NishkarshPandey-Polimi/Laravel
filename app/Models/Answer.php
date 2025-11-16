<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'text', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean', // since you're using a repeater
    ];

    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
}
