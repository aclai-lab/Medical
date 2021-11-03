<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Define_by extends Model
{
    protected $fillable =[
        'id_query',
        'id_user'
    ];


    public function queries()
    {
        return $this->hasMany('App\Query');
    }
}
