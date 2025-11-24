<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionnaireController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/questionnaires/{questionnaire}', [QuestionnaireController::class, 'show']);
Route::post('/questionnaires/{questionnaire}', [QuestionnaireController::class, 'submit']); // handles the POST
