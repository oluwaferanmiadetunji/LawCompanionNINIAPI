<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModel extends Model
{
    protected $table = "courses";
    public $timestamps = false;
    protected $fillable = [
        'course_name',
    ];
}
