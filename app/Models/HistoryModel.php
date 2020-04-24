<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryModel extends Model
{
    protected $table = "history";
    public $timestamps = false;
    protected $fillable = [
        'device_id',
        'createdAt',
        'numberCorrect',
        'course_name',
        'total'
    ];
}