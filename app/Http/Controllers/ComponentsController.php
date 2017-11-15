<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentsController extends Controller
{
    
    public function search(Request $request) 
    {
		$result["query"] = $request->input('query');
		
		//$components = \App\Component::search($result["query"])->get()->all(); // elastic
		
		$components = \App\Component::where('stored_name', 'like', '%'.$result["query"].'%')
               ->take(10)
               ->get();
               
		$result["suggestions"] = [];
		
		foreach ($components as $component)
		{
			$suggestion["value"] = $component->part_name;
			$suggestion["data"]["id"] = $component->id;
			$suggestion["data"]["producer"] = $component->producer ? $component->producer->display_name : 'noname';
			$result["suggestions"][] = $suggestion;
		}
		
		//dd($result["suggestions"]);
		
		return  $result;
    }
}
