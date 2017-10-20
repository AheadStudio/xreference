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
    
    /**
     * Filling references with data.
     *
     * @return \App\Reference with additional data
     */
	public function fillReference($reference){
		$component = $reference->component;
    	$replacement = $reference->replacement;
    	
    	$fullRating = '';
    	$rating =[];
    	$votes = $reference->votes;
    	foreach ($votes as $vote)
    	{
        	$rating[] = $vote->vote;
    	}
    	if (!empty($rating))
    	{
        	$countVotes = count($rating);
    		$fullRating = array_sum($rating) / $countVotes;
    		$fullRating = number_format($fullRating, 1, '.', ' ');
    		$fullRating .= ' ('.$countVotes.' votes) ';
    		$reference["fullRating"] = $fullRating;
    	}
    	$reference["noVoted"] = $votes->where('user_id', Auth::id())->isEmpty();
    	
    	return $reference;
	}
    
    /**
     * Adding subreferences to the reference.
     *
     * @return \App\Reference with additional data
     */
    public function getSubreferences($reference){
	    
	    foreach ($reference->replacement->references as $reference_2){
	    	$subreferences[] = $reference_2;
	    }
	    if (!empty($subreferences)){
	    	$reference->subreferences = collect($subreferences);
	    }
	    
	    return $reference;
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
			   
			    $components = \App\Component::where('stored_name', 'like', '%'.$partNumber.'%')
		               ->take(50)
		               ->select('id', 'part_name', 'stored_name')
		               ->get();
				// add the query to result
				$components->map(function ($component) use ($partNumber) {
				    $component['query'] = $partNumber;
				    return $component;
				});
				$componentsCollection = $componentsCollection->merge($components);
				
			}
			
			if ($componentsCollection->isEmpty()){
				foreach ($impossibleParts as $partNumber){
				    $components = \App\Component::where('stored_name', 'like', '%'.$partNumber.'%')
			               ->take(50)
			               ->select('id', 'part_name')
			               ->get();
					// add the query to result
					$components->map(function ($component) use ($partNumber) {
					    $component['query'] = $partNumber;
					    return $component;
					});
					$componentsCollection = $componentsCollection->merge($components);
				}
			}
			
			$componentsCollection = $componentsCollection->unique('id');
			
		} else {
		    return back()->with('error', 'No query!');
		}
	    
	    // get references for all found components
	    if (!empty($componentsCollection)){
		    
	    	
		    foreach ($componentsCollection as $component)
			{
				$query = $component["query"];
				
				// get references to that component
				$references = $component->references;
				// add the query to result
				$references->map(function ($reference) use ($query) {
				    $reference['query'] = $query;
				    return $reference;
				});
				// get references with that component as reference
				$referenceTo = $component->referenceTo;
				$referenceTo->map(function ($reference) use ($query) {
				    $reference['xquery'] = $query;
				    
				    return $reference;
				});
				$references = $references->merge($referenceTo);
				
				// apply filters
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
				
				
				
				
				$referenceCollection->map(function ($reference) {
				    
				    // fill references with data
				    $reference = $this->fillReference($reference);
				    
				    // get references to the references and so on (digging dipper)
				    if (!empty($reference->replacement->references)){
					    $reference = $this->getSubreferences($reference);
					    
					    if ($reference->subreferences){
						    //dd($reference->subreferences);
						    $reference->subreferences->map(function ($subreference) {
						    	$subreference = $this->fillReference($subreference);
						    	
						    	
						    	if (!Auth::guest() && !empty($subreference->replacement->references)){
							    	$subreference = $this->getSubreferences($subreference);
							    	
							    	if ($subreference->subreferences){
								    	$subreference->subreferences->map(function ($subreference2) {
								    		$subreference2 = $this->fillReference($subreference2);
								    	});
							    	}
						    	}
						    });
						}
				    }
				    
				    
				    
				    return $reference;
				});
				
				//dd($referenceCollection[0]);			                    

			}
			
	    } else {
			
			
		    
	    }
	    
	    //dd($referenceCollection);
        return view('result', compact('referenceCollection'));
    }
}
