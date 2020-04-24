<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionModel extends Model
{
    protected $table = "test_questions";
    public $timestamps = false;
    protected $fillable = [
        'course_name',
        'question',
        'incorrect_answer1',
        'incorrect_answer2',
        'incorrect_answer3',
        'correct_answer',
        'year',
        'explanation'
    ];
}
