<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    protected $fillable = [
        'user_id', 'name'
    ];
    
    // Voyager
    public function subscribers(){
	    return $this->belongsToMany(User::class, 'stock_user');
	}
	
	public function userId(){
	    return $this->belongsTo(User::class);
	}
	public function userIdList(){
		
		$isAdmin =  1;
		
		if ($isAdmin){
			return User::all();
		} else {
			return User::where('id', Auth::user()->id)->get();
		}
	}
	
	
	// Tinker
    public function owner(){
	    
	    return $this->belongsTo('App\User', 'user_id', 'id');
    
    }
    
    public function lookers(){
	    
	    return $this->belongsToMany('App\User');
    
    }
    
    public function references(){
	    
	    return $this->belongsToMany('App\Reference');
	    
    }
    
}
