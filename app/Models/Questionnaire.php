<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'questionnaires', 'is_active', 'passing_score', 'description']; // 'members' can be stored as JSON
    protected $casts = [
        'questionnaire' => 'array', // since you're using a repeater
    ];


    public function Question()
    {
        return $this->hasMany(Question::class);
    }
}
