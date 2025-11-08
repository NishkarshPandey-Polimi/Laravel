<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
        'passing_score',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'passing_score' => 'integer',
    ];

    protected $appends = [
        'passingScore',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function getPassingScoreAttribute(): int
    {
        return (int) $this->attributes['passing_score'];
    }

    public function setPassingScoreAttribute($value): void
    {
        $this->attributes['passing_score'] = (int) $value;
    }
}
