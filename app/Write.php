<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Write extends Model
{
    protected $fillable =[
        'id_author',
        'id_pubblication',
        'author_number'
    ];


    public function author()
    {
        return $this->hasMany('App\Author','id');
    }


    public function pubblication()
    {
        return $this->belongsTo('App\Pubblication');
    }

}
