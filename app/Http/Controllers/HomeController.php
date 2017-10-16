<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use URL;

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
	    $userId = Auth::id();
	    return view('home', compact('userId'));
    }
    
    public function elasticResult(Request $request)
    {
	    
	    $components = \App\Component::search($request->part_name)->get()->all();
	    
	    if (!empty($components)){
		    
	    
		    foreach ($components as $component)
			{
				$references = collect($component->references->all());
				
				if ($references->isNotEmpty())
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
    
    public function result(Request $request)
    {
	    //dd($request);
	    // make subqueries
	    $partName = preg_replace('/[^A-Za-z0-9]/', '', $request->part_name);
	    $queryLength = strlen($partName); 
	    $possibleParts[] = $partName;
	    $impossibleParts = [];
	    
	    for ($i = 1; $i <= $queryLength-3; $i++) {
		    $possibleParts[] = substr($partName, 0, -$i);
		    $impossibleParts[] = substr($partName, $i);
		}
		
		// get all components match that queries
		if (!empty($possibleParts)){
		    
		    $componentsCollection = Collection::make();
		    $referenceCollection = Collection::make();
		    
		    foreach ($possibleParts as $partNumber){
			   
			    $components = \App\Component::where('stored_name', $partNumber)
		               ->take(25)
		               ->select('id', 'part_name', 'stored_name')
		               ->get();
				
				$componentsCollection = $componentsCollection->merge($components);
				
			}
			
			if ($componentsCollection->isEmpty()){
				foreach ($impossibleParts as $partNumber){
				    $components = \App\Component::where('stored_name', $partNumber)
			               ->take(25)
			               ->select('id', 'part_name')
			               ->get();
					
					$componentsCollection = $componentsCollection->merge($components);
				}
			}
			
		} else {
		    return back()->with('error', 'No query!');
		}
	    
	    
	    // get references for all found components
	    if (!empty($componentsCollection)){
		    
	    	
		    foreach ($componentsCollection as $component)
			{
				$references = $component->references;
				$referenceTo = $component->referenceTo;
				$references = $references->merge($referenceTo);
				
				if ($references->isNotEmpty())
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
					
					$referenceCollection = $referenceCollection->merge($references);
				}
			}
			
	    } else {
			
			
		    
	    }
	    
        return view('result', compact('referenceCollection'));
    }
}
