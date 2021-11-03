<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{

    protected $fillable =[
        'id_query',
        'id_labels',
        'id_pubblication',
        'supervisor'
    ];

    public function pubblication()
    {
        return $this->hasMany('App\Pubblication','id','id_pubblication');
    }

    public function nFlag()
    {
        return $this->hasMany('App\LabelFlag','id','id_labels');
    }
    
}
