<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable =[
        'id_pubblication',
        'book_title',
        'editor'
    ];
}
