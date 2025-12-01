<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'name',
        'surname',
        'score',
        'result'
    ];



    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
}
