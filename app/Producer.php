<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    protected $fillable = ['name', 'display_name'];
    
    public function references(){
	    
	    return $this->hasMany('App\Reference');
    }
}

