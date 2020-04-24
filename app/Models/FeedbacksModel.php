<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbacksModel extends Model
{
    protected $table = "feedbacks";
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'feedback',
        'date',
    ];
}
