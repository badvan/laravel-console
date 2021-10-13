<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'method',
        'url',
        'status_code',
        'body'
    ];

    public $dates = ['date'];

    public $timestamps = false;
}
