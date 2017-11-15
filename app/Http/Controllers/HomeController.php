<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

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
    public function index(){
	    
	    $userId = Auth::id();
	    return view('home', compact('userId'));
    }
    
    
    /**
     * Send feedback to manager.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function feedback(Request $request)
    {
        
        $recipients = \App\User::where('recipient', true)
        	->select('email')
        	->get();
           
        $recipients = $recipients->pluck('email')->all();
        
		$data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'comment' => $request->comment
        ];
		
		if (!empty($recipients) && !empty($data)){
			Mail::to($recipients)->send(new FeedbackForm($data));
			
			$request->session()->flash('homepage_message','Your message has been sended!');
		} else {
			$request->session()->flash('homepage_message','Message not sended!');
		}
		
		return redirect('/');
    }
    
    
    /**
     * A.
     *
     * @return \Illuminate\Http\Response
     */
    public function result(Request $request){
    	
    	$allRefs = [];
    	
    	// fill search array with first search word
    	$words[0][] = $request->part_name;
    	
    	$initialPartName = $request->part_name; # !dunno why, check view, then delete
    	
    	// define depths of the search
    	if (Auth::guest()){
	    	$levels = 2;
	    } else {
		    $levels = 3;
	    }
	    
    	for ($i = 0; $i < $levels; $i++) {
	    	
	    	$result[$i] = Collection::make();
	    	
	    	foreach ($words[$i] as $key => $word){
		    	
		    	$references = [];
		    	
		    	// generate array of the queries from word
		    	$queries = $this->splitString($word);
		    	
		    	if ($i == 0){ 
			    	$remainQuries = $queries;
			    	$remainQuriesPattern = $queries;
			    }
		    	
		    	foreach ($queries as $key => $query){
			    	
			    	// save remain queries for First Level Pattern Search (end step)
			    	if ($i == 0 && ($currentQ = array_search($query, $remainQuries)) !== false){
				    	unset($remainQuries[$currentQ]);
					}
					
					// strict search
			    	$references = $this->searchStrict($query);
			    	
			    	// delete repeats
			    	$references = $references->whereNotIn('id', $allRefs);

			    	// stop on first strict match
			    	if ($references->isNotEmpty()){ 
				    	break 1;
				    }
				}
				
				
				// continue searching by pattern
				if ($references->isEmpty()){
					foreach ($queries as $key => $query){
				    	
				    	// save remain queries for First Level Pattern Search (end step)
				    	if ($i == 0 && ($currentQ = array_search($query, $remainQuries)) !== false){
					    	unset($remainQuriesPattern[$currentQ]);
						}
						
						// pattern search
				    	$references = $this->searchPattern($query);
				    	
				    	// delete repeats
						$references = $references->whereNotIn('id', $allRefs);
						
				    	// stop on first strict match
				    	if ($references->isNotEmpty()){
					    	//if ($i == 2){ dd($references, $allRefs); }
					    	break 1;
					    }
					}
		    	}
		    	
				// writh all searched references ids
				$allRefs = array_merge($allRefs, $references->pluck('id')->all());
				
		    	$result[$i] = $result[$i]->merge($references);
		    	
		    	
		    	// generate searching words from result
				$allWords = $this->generateStrings($references);
				
				// exclude repeats
				$words[$i+1] = array_diff($allWords, $words[$i], $words[0]); # !It needs to be more intelligent
				
	    	}
	    	
    	}
		
		
		
		// First Level Pattern Search
		if (!empty($remainQuries) || !empty($remainQuriesPattern)){
			
			if(empty($remainQuries)){
				$remainQuries = $remainQuriesPattern;
			}
			
			foreach ($remainQuries as $query){
				
				$finalReferences = $this->searchPattern($query);
		    	$finalReferences = $finalReferences->whereNotIn('id', $allRefs);
		    	
		    	// stop on first strict match
		    	if ($finalReferences->isNotEmpty()){
			    	//dd($finalReferences);
			    	break 1;
			    }
			}
			$result["FINAL"] = $finalReferences;
		}
		
		// apply filters
		foreach ($result as &$references){
			if ($references->isNotEmpty()){
				if ($request->factory_ref == 'on'){
					$references = collect($references->where('featured', 1)->all());
			    }
			    
			    if ($request->pin_ref == 'on'){
					$references = collect($references->where('type', 'direct')->all());
			    }
				
				if ($request->my_ref == 'on'){
				    $userId = Auth::id();
					$references = collect($references->where('user_id', $userId)->all());
			    }
			}
		}
		
		//dd($remainQuriesPattern);
		return view('result', compact('result', 'initialPartName'));
    }

    /**
     * S.
     *
     * @return Collection
     */
    public function generateStrings($references){
	    
	    $words = [];
	    
	    foreach ($references as $reference){
		    
		    // get references to REPLACEMENT
		    if ($reference->query){
				
				$words[] = $reference->replacement->part_name;
			// get references to COMPONENT  
		    } elseif ($reference->xquery){
			    
			    $words[] = $reference->component->part_name;
			
			// how the hell..
		    } else {
			    $words[] = 'This is a bug!';
		    }
	    }
	    
	    return($words);
	}    
    
    /**
     * Search references by part number ant show them.
     *
     * @return Collection
     */
    public function searchStrict($query){
		
		$references = Collection::Make(); 
		
		// take components by part name
		$components = \App\Component::where('stored_name', $query["QUERY"])
           ->take(50)
           ->select('id', 'part_name', 'stored_name')
           ->get();
	    
	    // get references
	    if ($components->isNotEmpty()){
		    foreach ($components as $component){
			    
			    // get references to that component and mark them
				$references = $component->references;
				$references->map(function ($reference) use ($query) {
					$reference = $this->fillReference($reference);
				    $reference['query'] = $query["QUERY"];
				    $reference['initialPartName'] = $query["PART_NAME"];
				    return $reference;
				});
				
				// get references with that component as reference
				$referenceTo = $component->referenceTo;
				$referenceTo->map(function ($reference) use ($query) {
					$reference = $this->fillReference($reference);
				    $reference['xquery'] = $query["QUERY"];
				    $reference['initialPartName'] = $query["PART_NAME"];
				    
				    return $reference;
				});
				
				$references = $references->merge($referenceTo);
				
			}
		}
		
		//dd($references);
		return $references;
		
	}
    
    /**
     * Search references by part number ant show them.
     *
     * @return Collection
     */
    public function searchPattern($query){
	    
	    $components = Collection::make();
		$result = Collection::make();
		
	    // get components
		$components = \App\Component::where('stored_name', 'like', '%'.$query["QUERY"].'%')
               ->take(50)
               ->select('id', 'part_name', 'stored_name')
               ->get();
		
		// get references
		if ($components->isNotEmpty()){
			
			foreach ($components as $component){
				
				$references = Collection::make();
				
				// get references to that component and mark them
				$references = $component->references;
				$references->map(function ($reference) use ($query) {
					$reference = $this->fillReference($reference);
				    $reference['query'] = $query["QUERY"];
				    $reference['initialPartName'] = $query["PART_NAME"];
				    return $reference;
				});
				
				// get references with that component as reference
				$referenceTo = $component->referenceTo;
				$referenceTo->map(function ($reference) use ($query) {
					$reference = $this->fillReference($reference);
				    $reference['xquery'] = $query["QUERY"];
				    $reference['initialPartName'] = $query["PART_NAME"];
				});
				
				$references = $references->merge($referenceTo);
				$result = $result->merge($references);
			}
		}
		
		return $result;
	}
    
    public function resultOld2(Request $request)
    {
		$references = Collection::make();
		$similarReferences = Collection::make();
		$componentsLike = [];
		
		// get array of possible queries
		$queries = $this->splitString($request->part_name);
	    
	    // get exact matching components 
	    foreach ($queries as $key => $query){
		    
		    unset($queries[$key]);
		    $safeQueries[] = $query;
		    
		    $components = \App\Component::where('stored_name', $query["QUERY"])
               ->take(50)
               ->select('id', 'part_name', 'stored_name')
               ->get();
		    
		    
		    if ($components->isNotEmpty()){
			    
			    foreach ($components as $component){
					
					// get references to that component and mark them
					$references = $component->references;
					$references->map(function ($reference) use ($query) {
					    $reference['query'] = $query["QUERY"];
					    $reference['initialPartName'] = $query["PART_NAME"];
					    return $reference;
					});
					
					// get references lvl 1 with that component as reference
					$referenceTo = $component->referenceTo;
					//$referenceTo->map(function ($reference) use ($query) {
					foreach ($referenceTo as &$reference){
					    $reference['xquery'] = $query["QUERY"];
					    $reference['initialPartName'] = $query["PART_NAME"];
					    
					    // get references lvl 2
					    $references2 = $reference->getSubreferences('component');
					    // fill them with info
					    $references2->map(function ($reference2) {
						    $reference2 = $this->fillReference($reference2);
						    
					    });
					    $reference['subreferences'] = $references2;  
					    
					    //return $reference;
					}
					
					$references = $references->merge($referenceTo);
				}	
				
				break; // stop searching on a first match
			}
	    }
	    
	    
	    //dd($references2);
	    
	    // get references level 3
	    $references3 = Collection::make();
		if (!Auth::guest()){
			
			$allRefs = [];
			// writh all level 1 references ids
			$allRefs = array_merge($allRefs, $references->pluck('id')->all());
			// write all lvl 2 references ids
			foreach ($references as $reference){	
				if ($reference->subreferences){
					$allRefs = array_merge($allRefs, $reference->subreferences->pluck('id')->all());	
				}
			}
			
			$references3 = Collection::make();
			
			// get all lvl 3 references
			foreach ($references as $reference){
				
				if ($reference->subreferences){
					$references2 = $reference->getSubreferences('component');
					
					//dd($references2);
					
					foreach ($references2 as $reference2){
						$references3 = $references3->merge($reference2->getSubreferences());
					}
				}
			}
			//dd($references3);
			if ($references3->isNotEmpty()){
				
				//$references3 = $references3->unique();
				//$references3 = $references3->whereNotIn('id', $allRefs);
				
				//dd($references3, $allRefs);	
			}
			//dd($references3, $allRefs);
			//dd($referenceCollection);		                    
		}
		
		
		
		// for not exact result
		if (empty($queries)){
			$queries = $safeQueries;
		}
		
		// get next query by substring
		foreach ($queries as $query){
			
			// get components
			$componentsLike = \App\Component::where('stored_name', 'like', '%'.$query["QUERY"].'%')
	               ->take(50)
	               ->select('id', 'part_name', 'stored_name')
	               ->get();
			$componentsLike = $componentsLike->whereNotIn('id', $components->pluck('id'));
			
			// get references
			
			
			if ($componentsLike->isNotEmpty()){
				$referenceLike = Collection::make();
				$referenceLikeTo = Collection::make();
				
				foreach ($componentsLike as $componentLike){
					// get references to that component and mark them
					$referencesLike = $componentLike->references;
					$referencesLike->map(function ($reference) use ($query) {
					    $reference['query'] = $query["QUERY"];
					    $reference['initialPartName'] = $query["PART_NAME"];
					    return $reference;
					});
					
					// get references lvl 1 with that component as reference
					$referenceLikeTo = $componentLike->referenceTo;
					$referenceLikeTo->map(function ($reference) use ($query) {
					    $reference['xquery'] = $query["QUERY"];
					    $reference['initialPartName'] = $query["PART_NAME"];
					});
					
					
					$referencesLike = $referencesLike->merge($referenceLikeTo);
					$test[] = $referencesLike;
					$similarReferences = $similarReferences->merge($referencesLike);
				}
				
				break; // stop search components after first result
			}
		}
		
	    //dd($similarReferences);
	    
	    return view('result', compact('references', 'references3', 'similarReferences', 'initialPartName'));
		
	}
    
    
    
    /**
     * Subdivide given string to sting with length 3 by cutting chars from
     * the end and then from the beginning. Wright result with and without
     * symbols.
     *
     * @param  string  $string
     * @return array
     */
    private function splitString($string){
	    
	    
	    $partName = preg_replace('/[^A-Za-z0-9]/', '', $string);
	    $queryLength = strlen($partName);
	    
	    // wright original query
	    $fullString[0]["QUERY"] = $partName;
		$fullString[0]["PART_NAME"] = $string;
	    
	    $possibleParts = [];
	    $impossibleParts = [];
	    
	    // defence from long strings
	    if ($queryLength > 50){
		    return collect(["Error" => "Query must be less then 50 characters"]);
	    }
	    
	    // wright out cropped part name with and without symbols
		$k = 0; // last chars
		$j = 0; // first chars
	    
	    $sansLast = [];
	    $sansFirst = [];
	    
	    for ($i = 1; $i <= $queryLength-3; $i++) {
		    
		    // cut by one symbol from the end
		    $croppedLast = substr($string, 0, -$i-$k);
		    if (substr($croppedLast, -1) == '-' ||
		    	substr($croppedLast, -1) == '/' ||
		    	substr($croppedLast, -1) == '.' ||
		    	substr($croppedLast, -1) == ',' ||
		    	substr($croppedLast, -1) == '(' ||
		    	substr($croppedLast, -1) == ')' )
		    {
				$k++;
				$croppedLast = trim($croppedLast, "()-,.");
			}
			
			// we would search by qury, but show the part name
			$sansLast[$i]["QUERY"] = substr($partName, 0, -$i);
		    $sansLast[$i]["PART_NAME"] = $croppedLast;
			
			// cut by one symbol from the beginning
			$croppedFirst = substr($string, $i+$j);
			if (substr($croppedFirst, 0, 1) == '-' ||
		    	substr($croppedFirst, 0, 1) == '.' ||
		    	substr($croppedFirst, 0, 1) == ',' ||
		    	substr($croppedFirst, 0, 1) == '(' ||
		    	substr($croppedFirst, 0, 1) == ')' )
		    {
				$j++;
				$croppedFirst = trim($croppedFirst, "()-,.");
			}
			
		    $sansFirst[$i]["QUERY"] = substr($partName, $i);
		    $sansFirst[$i]["PART_NAME"] = $croppedFirst;
		    
		}
		
		$subdividedString = array_merge($fullString, $sansLast, $sansFirst);
		
		return $subdividedString;
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
    public function getSubreferences($reference, $from = "replacement"){
	    
	    foreach ($reference->$from->references as $reference_2){
	    	$subreferences[] = $reference_2;
	    }
	    if (!empty($subreferences)){
	    	$reference->subreferences = collect($subreferences);
	    }
	    
	    return $reference;
    }
    
    public function resultOld(Request $request)
    {
	    //dd($request);
	    // make subqueries
	    $initialPartName = $request->part_name;
	    $partName = preg_replace('/[^A-Za-z0-9]/', '', $request->part_name);
	    $queryLength = strlen($partName); 
	    $possibleParts = [];
	    $impossibleParts = [];
	    $componentsCollection = Collection::make();
		$referenceCollection = Collection::make();
	    
	    
		
		$components = \App\Component::where('stored_name', $partName)
               ->take(70)
               ->select('id', 'part_name', 'stored_name')
               ->get();
		// add the query to result
		$components->map(function ($component) use ($partName, $initialPartName) {
		    $component['query'] = $partName;
		    $component['initialQuery'] = $initialPartName;
		    return $component;
		});
		$componentsCollection = $componentsCollection->merge($components);
		
		//dd($componentsCollection->isEmpty());
		
		// get all components match that queries
		if ($componentsCollection->isEmpty()){
			
		    //dd($componentsCollection);
		    $croppedPartName = $initialPartName;
		    $k = 0;
		    for ($i = 1; $i <= $queryLength-3; $i++) {
			    
			    $croppedPartName = substr($initialPartName, 0, -$i-$k);
			    if (substr($croppedPartName, -1) == '-' ||
			    	substr($croppedPartName, -1) == '.' ||
			    	substr($croppedPartName, -1) == ',' ||
			    	substr($croppedPartName, -1) == '(' ||
			    	substr($croppedPartName, -1) == ')' ){
					$k++;
					$croppedPartName = trim($croppedPartName, "()-,.");
				}
			    $possibleParts[$i]["QUERY"] = substr($partName, 0, -$i);
			    $possibleParts[$i]["PART_NAME"] = $croppedPartName;
			    
			    $impossibleParts[$i] = substr($partName, $i);
			}
			//dd($possibleParts);
			
		    foreach ($possibleParts as $partNumber){
			   
			    $components = \App\Component::where('stored_name', 'like', '%'.$partNumber["QUERY"].'%')
		               ->take(50)
		               ->select('id', 'part_name', 'stored_name')
		               ->get();
				// add the query to result
				$components->map(function ($component) use ($partNumber) {
				    $component['query'] = $partNumber["QUERY"];
				    $component['initialQuery'] = $partNumber["PART_NAME"];
				    return $component;
				});
				$componentsCollection = $componentsCollection->merge($components);
				if ($componentsCollection->isNotEmpty()){
					break;
				}
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
			
		}
	    
	    // get references for all found components
	    if ($componentsCollection->isNotEmpty()){
		    
	    	
		    foreach ($componentsCollection as $component)
			{
				$query = $component["query"];
				$initialQuery = $component["initialQuery"];
				
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
				
				// add the initial query to result
				$references->map(function ($reference) use ($initialQuery) {
				    $reference['initialPartName'] = $initialQuery;
				    return $reference;
				});
				
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
				
				
				$referenceCollection->map(function ($reference) use ($possibleParts) {
				    
				    // fill references with data
				    $reference = $this->fillReference($reference);
				    //dd($reference);
				    if (empty($possibleParts)){ // only when part number is equal to query
					    // get references to the references and so on (digging dipper)
					    if (!empty($reference->replacement->references) && !empty($reference['query'])){
						    
						    $reference = $this->getSubreferences($reference);
						    
						    if ($reference->subreferences){
							    //dd($reference->subreferences);
							    $reference->subreferences = $reference->subreferences->where('id', '<>', $reference->id);
							    //dd($test);
							    $reference->subreferences->map(function ($subreference) {
							    	$subreference = $this->fillReference($subreference);
							    	
							    	
							    	if (!Auth::guest() && !empty($subreference->replacement->references)){
								    	$subreference = $this->getSubreferences($subreference);
								    	$subreference->subreferences = $subreference->subreferences->where('id', '<>', $reference->id);
								    	if ($subreference->subreferences){
									    	$subreference->subreferences->map(function ($subreference2) {
									    		$subreference2 = $this->fillReference($subreference2);
									    	});
								    	}
							    	}
							    });
							}
					    }
					    
					    // add references level 2
					    if (!empty($reference->component->references) && !empty($reference['xquery'])){
						    
						    $reference = $this->getSubreferences($reference, "component");
						    //dd($reference);
						    $reference->subreferences = $reference->subreferences->where('id', '<>', $reference->id);
							//dd($reference);
						    if ($reference->subreferences){
							    //dd($reference->subreferences);
							    $reference->subreferences->map(function ($subreference) {
							    	$subreference = $this->fillReference($subreference);
							    	
							    	// add references level 3
							    	if (!Auth::guest() && !empty($subreference->replacement->references)){
								    	
								    	$subreference2 = $this->getSubreferences($subreference);
								    	dd($subreference);
								    	if ($subreference->subreferences){
									    	$subreference->subreferences->map(function ($subreference2) {
									    		$subreference2 = $this->fillReference($subreference2);
									    	});
								    	}
							    	}
							    });
							}
					    }					   
				    }
				    
				    return $reference;
				});
				//dd($referenceCollection);
				// filter references level 3
				if (!Auth::guest()){
					$allRefs = [];
					$references3 = Collection::make();
					// writh all level 1 references ids
					$allRefs = array_merge($allRefs, $referenceCollection->pluck('id')->all());
					
					// write all lvl 2 references ids
					foreach ($referenceCollection as $reference){	
						if ($reference->subreferences){
							$allRefs = array_merge($allRefs, $reference->subreferences->pluck('id')->all());					
						}
					}
					//$subreferences = Collection::make();
					
					// get all lvl 3 references
					foreach ($referenceCollection as $reference){
						
						if ($reference->subreferences){
							foreach ($reference->subreferences as $reference2){
								//dd($reference2);
								$subreferences = $reference2->subreferences;
								
								if ($subreferences){
									
									$subreferences = $subreferences->whereNotIn('id', $allRefs);
									$allRefs = array_merge($allRefs, $subreferences->pluck('id')->all());
									$references3 = $references3->concat($subreferences);
									
									//dd($references3, $allRefs);	
								}
							}
						}
					}
					//dd($references3, $allRefs);
					//dd($referenceCollection);		                    
				}
			}
			
	    } else {
			
			
		    
	    }
	    
	    //dd($referenceCollection);
	   //echo "<pre>"; print_r ($referenceCollection); echo "</pre>";
        return view('result', compact('referenceCollection', 'references3', 'initialPartName'));
    }
}
