<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['part_name', 'producer'];
    
    public function references(){
	    
	    return $this->hasMany('App\Reference');
    }
    
    public function referenceTo(){
	    
	    return $this->hasMany('App\Reference', 'ref_component_id');
    }
}
