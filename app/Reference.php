<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
    	'component_id',
    	'ref_component_id',
    	'user_id',
    	'rating_id',
    	'type',
    	'featured'
    ];
    
    public function creator(){
	    
	    return $this->belongsTo('App\User', 'user_id');
    }
    
    public function component(){
	    
	    return $this->belongsTo('App\Component', 'component_id');
    }
    
    public function replacement(){
	    
	    return $this->belongsTo('App\Component', 'ref_component_id');
    }
    
    public function votes(){
	    
	    return $this->hasMany('App\Rating');
    }
    
    public function stocks(){
	    
	    return $this->belongsToMany('App\Stock');
	    
    }
}
