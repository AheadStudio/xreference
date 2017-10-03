<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    return view('home');
    }
    
    public function result(Request $request)
    {
	    
	    $components = \App\Component::search($request->part_name)->get()->all();
	    
	    if (!empty($components)){
		    
	    
		    foreach ($components as $component)
			{
				$references = collect($component->references->all());
				
				if (!empty($references))
				{
					if ($request->factory_ref == 'on')
				    {
						$references = collect($references->where('featured', 1)->all());
				    }
				    
				    if ($request->pin_ref == 'on')
				    {
						$references = collect($references->where('type', 'direct')->all());
				    }
					
					if ($request->my_ref == 'on')
				    {
					    $userId = Auth::id();
						$references = collect($references->where('user_id', $userId)->all());
				    }
					
					$referenceCollection[$component->id] = $references;
				}
			}
			
	    } else {
			
			$referenceCollection = [];
		    
	    }
	    
        return view('result', compact('referenceCollection'));
    }
}
