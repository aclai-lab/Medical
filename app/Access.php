<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable =[
        'id_user',
        'id_database',
        'api_key',
        'user_name',
        'db_password'
    ];


    public function database()
    {
        return $this->belongsTo('App\Database');
    }
}
