<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = "users";
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'address',
        'device_id',
        'r_date',
        'e_date',
        'count',
        'duration'
    ];
}
