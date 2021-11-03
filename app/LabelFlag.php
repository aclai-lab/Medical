<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelFlag extends Model
{
    protected $fillable =[
        'id_query',
        'name'
    ];


    public function labels()
    {
        return $this->hasMany('App\Label','id_labels');
    }
}
