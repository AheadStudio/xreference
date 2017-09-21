<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'vote', 'user_id', 'reference_id'
    ];
    
    public function reference(){
	    
	    return $this->belongsTo('App\Reference');
	    
    }
    
    public function user(){
	    
	    return $this->belongsTo('App\User');
	    
    }
    
    public function userId(){
	    return $this->belongsTo(User::class);
	}
}
