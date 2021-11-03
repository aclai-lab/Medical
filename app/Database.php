<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    protected $fillable =[
        'name',
        'webpage'
    ];


    public function access()
    {
        return $this->hasMany('App\Access');
    }
}
