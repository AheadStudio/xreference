<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use Searchable;
	
	protected $indexConfigurator = ReferenceIndexConfigurator::class;
	
	protected $searchRules = [
        //
    ];
    
    protected $mapping = [
        'properties' => [
            'text' => [
                'type' => 'string',
                'fields' => [
                    'raw' => [
                        'type' => 'string',
                        'index' => 'not_analyzed',
                    ]
                ]
            ],
        ]
    ];    
    
    protected $fillable = ['part_name', 'producer'];
    
    public function references(){
	    
	    return $this->hasMany('App\Reference');
    }
    
    public function referenceTo(){
	    
	    return $this->hasMany('App\Reference', 'ref_component_id');
    }
    
    public function producer()
    {
	    return $this->belongsTo('App\Producer');
    }
}
