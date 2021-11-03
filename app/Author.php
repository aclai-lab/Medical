<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable =[
        'string',
        'first_name',
        'last_name',
        'email',
        'web_page'
    ];

    public function write()
    {
        return $this->hasMany('App\Write','id_author');
    }


}
