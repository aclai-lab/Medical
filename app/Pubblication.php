<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pubblication extends Model
{
    protected $fillable =[
        'title',
        'abstract',
        'pubblication_year',
        'pages'
    ];

    public function write()
    {
        return $this->hasMany('App\Write','id_pubblication');
    }

    public function discover()
    {
        return $this->hasMany('App\Discover','id_pubblication');
    }

    public function lbl()
    {
        return $this->hasMany('App\Label','id_pubblication','id');
    }

}
