<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rating;
use App\Reference;
use App\Producer;
use App\Component;
use Excel;

class ReferencesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reference.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reference.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request;
        
        $user = Auth::user();
		$data = [
            'component_id' => $input->component_id,
            'ref_component_id' => $input->ref_component_id,
            'user_id' => $user->id,
            'type' => $input->type,
            'featured' => 0, // only if user approuved
            'status' => $input->status
        ];

		Reference::create($data);

        $request->session()->flash('reference_created','New reference has been created!');
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('reference.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function rate(Request $request)
    {
		$user = Auth::user();

        $data = [

            'vote' => $request->rate,
            'user_id'=> $user->id,
            'reference_id' =>$request->reference_id

        ];
		
		Rating::create($data);

        $request->session()->flash('reply_message','Your reply has been submitted and is waiting moderation');
	    
	    return redirect('/');
	    //return view('result', compact('referenceCollection'));
	    //return redirect()->back()->withInput();
	    //return back()->withInput();
    }
    
    /**
     * Export data to excel.
     *
     */
    public function downloadExcel(Request $request)
	{
		$type = 'xls';
		$user = $request->user;
		$components = json_decode($request->components);
		//dd($components);
		$data = Reference::when($user, function ($query) use ($user) {
                    return $query->where('user_id', $user);
                })
			->when($components, function ($query) use ($components) {
                return $query->whereIn('references.id', $components);
            })
			->join('components as com', 'references.component_id', '=', 'com.id')
			->join('producers as com_producers', 'com.producer_id', '=', 'com_producers.id')
			->join('components as ref', 'references.ref_component_id', '=', 'ref.id')
			->join('producers as ref_producers', 'ref.producer_id', '=', 'ref_producers.id')
			->select('com.part_name as Part number',
					 'com_producers.display_name as Manufacturer',
					 'com.status as Status',
					 'com.package as Package',
					 'ref.part_name as reference_name',
					 'ref_producers.display_name as Xreference manufacturer',
					 'ref.status as Xreference status',
					 'ref.package as Xreference package',
					 'type',
					 'featured',
					 'comment')
			->get()
			->toArray();
		
		//dd($data);
		return Excel::create('xreference', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}
    
    /**
     * Import data from excel.
     *
     */
    public function import(Request $request)
    {
        if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();

			$data = Excel::load($path, function($reader) {})->get();

			if(!empty($data) && $data->count()){
				
				$user = Auth::user();
				
				foreach($data as $reference)
				{
					//dd($reference);
					if ($reference->part_number && $reference->xreference_part_number){
						
						// Get from DB or create producers
						if ($reference->manufacturer){
							$comProducer = Producer::firstOrCreate(
							    ['name' => strtoupper($reference->manufacturer)], ['display_name' => $reference->manufacturer]
							);
						} else {
							$comProducer = Producer::firstOrCreate(['name' => 'NONAME'], ['display_name' => 'NoName']);
						}
						if ($reference->xreference_manufacturer){
							$refProducer = Producer::firstOrCreate(
							    ['name' => strtoupper($reference->xreference_manufacturer)], ['display_name' => $reference->xreference_manufacturer]
							);
						} else {
							$refProducer = Producer::firstOrCreate(['name' => 'NONAME'], ['display_name' => 'NoName']);
						}
						
						// Get from DB or create both components
						$component = Component::firstOrCreate(
						    ['stored_name' => preg_replace('/[^A-Za-z0-9]/', '', $reference->part_number)],
						    [  	'part_name' => $reference->part_number,
						    	'producer_id' => $comProducer->id,
						    	'package' => $reference->package,
						    	'status' => $reference->status
						    ]
						);
						$refComponent = Component::firstOrCreate(
						    ['stored_name' => preg_replace('/[^A-Za-z0-9]/', '', $reference->xreference_part_number)],
						    [	'part_name' => $reference->xreference_part_number,
						    	'producer_id' => $refProducer->id,
						    	'package' => $reference->package,
						    	'status' => $reference->status
						    ]
						);
						
						// Create reference
						$xreference = Reference::firstOrCreate(
						    [
						    	'component_id' => $component->id,
								'ref_component_id' => $refComponent->id,
						    ],
						    [
						    	'user_id' => $user->id,
								'type' => strtolower($reference->replacement_type), // add validation
								'comment' => $reference->comment,
								'featured' => 0 // add checking
						    ]
						);
					}
					//dd($xreference);
				}
				
				
				
				

				
				if(!empty($xreference)){
					//Item::insert($insert);
					return back()->with('success','Record has been uploaded.');
				}

			}

		}

		return back()->with('error','Please Check your file, Something is wrong there.');
    }
}
