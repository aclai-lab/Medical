<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discover extends Model
{
    protected $fillable =[
        'id_query',
        'id_pubblication',
        'source_db'
    ];


    public function pubblication()
    {
        return $this->hasMany('App\Pubblication','id','id_pubblication');
    }
}
