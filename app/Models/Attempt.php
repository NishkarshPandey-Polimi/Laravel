<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'questionnaire_id',
        'started_at',
        'submitted_at',
        'score_cached',
        'wrong_count_cached',
        'passed',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score_cached' => 'integer',
        'wrong_count_cached' => 'integer',
        'passed' => 'boolean',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
