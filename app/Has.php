<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Has extends Model
{
    protected $fillable =[
        'id_author',
        'id_affiliation',
        'has_year'
    ];


    public function affilitiaon()
    {
        return $this->belongsTo('App\Affiliation');
    }

    public function author()
    {
        return $this->belongsTo('App\Author');
    }
}
