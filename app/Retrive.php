<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retrive extends Model
{
    protected $fillable =[
        'id_query',
        'id_author'
    ];
}
