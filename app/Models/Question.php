<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['questionnaire_id', 'text']; // 'members' can be stored as JSON

    public function Questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function Answer()
    {
        return $this->hasMany(Answer::class);
    }

    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }
}
