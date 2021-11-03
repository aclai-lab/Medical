<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable =[
        'id_pubblication',
        'name',
        'volume',
        'issue'
    ];
}
