<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $fillable =[
        'name',
        'description_long',
        'description_short',
        'project_type',
        'pre_exc',
        // 'creationdate',
        'latest_exc_date',
        'ret_start',
        'ret_max',
        'name',
        'exec_in_progress',
        'train_in_progress',
        'seed',
        'accuracy',
        // 'querydate',
        'place',
        'key_phrases',
        'string'
    ];


    public function defined_by()
    {
        return $this->hasMany('App\Define_by');
    }

}
