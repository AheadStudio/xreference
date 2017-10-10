<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use TCG\Voyager\Models\User as VoyagerUser;

class User extends VoyagerUser //Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'company_name', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
/*
    public function roles(){
	    
	    return $this->belongsToMany('App\Role');
    }
*/
    
    public function references(){
	    
	    return $this->hasMany('App\Reference');
	    
    }
    
    public function votes(){
	    
	    return $this->hasMany('App\Rating');
	    
    }
    
    public function ownstocks(){
	    
	    return $this->hasMany('App\Stock');
	    
    }
    
    public function allstocks(){
	    
	    return $this->belongsToMany('App\Stock');
	    
    }
}
