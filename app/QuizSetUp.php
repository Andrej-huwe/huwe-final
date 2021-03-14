<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizSetUp extends Model
{
    protected $fillable=['question', 'correct_answer', 'incorrect_answer'];
}
