<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Specify the table (optional because Laravel can guess)
    protected $table = 'messages';

    // Allow mass assignment (so we can use create() easily)
    protected $fillable = ['name',  'message'];
}
