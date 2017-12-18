@extends('layouts.app')

@section('content')

{!! Form::open(['method'=>'GET', 'action'=> 'HomeController@result', 'files'=>false, 'class' => 'form-horizontal']) !!}
	<div class="form-group">
		<div class="form-row">
	        <div class="col-md-10">
	        	{!! Form::text('part_name', null, ['class'=>'form-control form-control-lg', 'placeholder'=>'Searching request, minimum 3 symbols', 'pattern'=>'.{3,}', 'required']); !!}
	        </div>
			{!! Form::submit('Search', ['class' => 'col-md-2 btn btn-success btn-lg']); !!}
		</div>
    </div>
	@if (!Auth::guest())
    	<div class="form-group">
        	<div class="form-row">
	        	<div class="col-md-4">
	                <div class="form-check form-check-inline">
		                <label class="form-check-label">
	                		{!! Form::checkbox('my_ref', null, false, ['id'=>'my_ref', 'class'=> 'form-check-input']); !!} 
	                		Only my references
		                </label>
	                </div>
	        	</div>
	        	<div class="col-md-4">
	                <div class="form-check form-check-inline">
		                <label class="form-check-label">
	                		{!! Form::checkbox('pin_ref', null, false, ['id'=>'pin_ref', 'class'=> 'form-check-input']); !!} 
	                		Only pin-to-pin references
		                </label>
	                </div>
	        	</div>
                <div class="col-md-4">
	                <div class="form-check form-check-inline">
		                <label class="form-check-label">
	                		{!! Form::checkbox('factory_ref', null, false, ['id'=>'factory_ref', 'class'=> 'form-check-input']); !!} 
							Only manufacturer references
		                </label>
	                </div>
                </div>
            </div>            
        </div>
    @endif
{!! Form::close() !!}

@if(!empty($result))

    <table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th scope="col">Part number</th>
				<th scope="col">Manufacturer</th>
				<th scope="col">XReference part number</th>
				<th scope="col">XReference manufacturer</th>
				<th scope="col">Replacement type</th>
				<th scope="col">From manufacturer</th>
				<th scope="col">Rating</th>
			</tr>
		</thead>
		<tbody>
		
		<!-- Lists (levels) -->
		@foreach ($result as $key => $references)
			
			<!-- References -->
            @foreach ($references as $reference)
                
            	@php
            		
                	$component = $reference->component;
                	$replacement = $reference->replacement;
                	if ($reference["query"]){
                    	$keyword = $reference->query;
                	} else {
                    	$keyword = $reference->xquery;
                	}
                	
                	//$searchedNameComponent = preg_replace("/\w*".preg_quote($reference->initialPartName)."\w*/i", "<b>$0</b>", $component->part_name);
                	//$searchedNameReplaced = preg_replace("/\w*".preg_quote($reference->initialPartName)."\w*/i", "<b>$0</b>", $replacement->part_name);
                	//dd($references);
                	
                	// coloring
                	if ($key === "FINAL"){
                    	$class = "success";
                	} elseif ($key == 0) {
                    	$class = "info";
                	} elseif ($key == 1) {
                    	$class = "danger";
                	} else {
                    	$class = "warning";
                	}
                	
            	@endphp
            	
            	<tr class="table-{{ $class }}">
    				<td>
    					@if ($reference["query"])
        					<b>{!! $component->part_name !!}</b>
        				@else
        					{{ $component->part_name }}
        				@endif 
        			</td>
    				<td>{{ $component->producer ? $component->producer->display_name : 'noname'}}</td>
    				<td>
    					@if ($reference["xquery"])
        					<b>{!! $replacement->part_name !!}</b>
        				@else
        					{{ $replacement->part_name }}
        				@endif 
    				</td>
    				
    				
    				<td>{{ $replacement->producer ? $replacement->producer->display_name : 'noname'}}</td>
    				<td>{{ $reference->type }}</td>
    				<td>{{ $reference->featured ? 'Yes' : 'No'}}</td>
    				<td>{{ $reference->fullRating }}
    					@if (!Auth::guest() && $reference->noVoted)
        					<a href="" data-id="{{ $reference->id }}" class="btn btn-vote btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm">Vote</a>
        				@endif
    				</td>
    			</tr>
    		@endforeach	
    	
    	@endforeach
        </tbody>
        
    </table>

@else
    
	<p>No results</p>                 	
	
@endif


<!-- Small modal -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			{!! Form::open(['method' => 'POST', 'action'=> 'ReferencesController@rate']) !!}
	        	
	        	<div class="modal-body">
	        		{{ csrf_field() }}
	        		{!! Form::hidden('reference_id', null, ['class' => "rate-reference"]); !!}
	        		{!! Form::hidden('query', null); !!}
	        		<div class="form-group text-center">
		                {!! Form::label('rate', 'Rate this reference', ['class' => 'control-label']) !!}
		                {!! Form::selectRange('rate', 1, 5, null, ['class'=>'rating-bar'])!!}
		            </div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					{!! Form::submit('Vote', ['class' => 'btn btn-primary']); !!}
				</div>
				
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="alert alert-light" role="alert">
	<p><i>On our website, all information is provided by users and requires verification before use. All decisions taken about the use of a replacement are taken by you alone and we are not responsible for them.</i></p>
</div>

@endsection
