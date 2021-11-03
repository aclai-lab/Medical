<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $fillable =[
        'string',
        'department',
        'faculty',
        'institute',
        'address',
        'city',
        'country'
    ];

    public function has_aff()
    {
        return $this->hasMany('App\Has');
    }
}
