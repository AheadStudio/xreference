@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                
                <div class="panel-body">
			        {!! Form::open(['method'=>'POST', 'action'=> 'HomeController@result', 'files'=>false, 'class' => 'form-horizontal']) !!}
			        	{{ csrf_field() }}
			        	
			        	@if (!Auth::guest())
				        	<div class="form-group">
				                <div class="col-md-1 text-right">
				                	{!! Form::checkbox('my_ref', null, false, ['id'=>'my_ref']); !!}
				                	
				                </div>
				                {!! Form::label('my_ref', 'Only my references', ['class' => 'col-md-3']) !!}
				            
				                
				                <div class="col-md-1 text-right">
				                	{!! Form::checkbox('pin_ref', null, false, ['id'=>'pin_ref']); !!}
				                </div>
				                {!! Form::label('pin_ref', 'Only pin-to-pin references', ['class' => 'col-md-3']) !!}
				                
				                
				                <div class="col-md-1 text-right">
				                	{!! Form::checkbox('factory_ref', null, false, ['id'=>'factory_ref']); !!}
				                </div>
				                {!! Form::label('factory_ref', 'Only manufacturer references', ['class' => 'col-md-3']) !!}
				                
				            </div>
			            @endif
			            
			        	<div class="form-group">
			                <div class="col-md-9">
			                	{!! Form::text('part_name', null, ['class'=>'form-control', 'placeholder'=>'Searching request, minimum 3 symbols', 'pattern'=>'.{3,}', 'required']); !!}
			                </div>
							
							{!! Form::submit('Search', ['class' => 'col-md-2 btn btn-primary']); !!}
							
                        </div>
					
					{!! Form::close() !!}
                </div>
                
            </div>
	    </div>
	</div>
    
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading">Results</div>

                <div class="panel-body">
                    
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
					
					@if(!empty($referenceCollection))
					
	                    <table class="table table-hover">
	                		
	                		<thead>
	                			<tr>
	                				<th>Part number</th>
	                				<th>Manufacturer</th>
	                				<th>XReference part number</th>
	                				<th>XReference manufacturer</th>
	                				<th>Replacement type</th>
	                				<th>From manufacturer</th>
	                				<th>Rating</th>
	                			</tr>
	                		</thead>
	                		
	                		<tbody>
			                    @foreach ($referenceCollection as $reference)
				                    
				                    	@php
					                    	$component = $reference->component;
					                    	$replacement = $reference->replacement;
				                    	@endphp
				                    	
				                    	<tr class="">
			                				<td>
			                					@if ($reference["query"])
				                					<b>{{ $component->part_name }}</b>&nbsp
				                					@if (!empty($reference->subreferences))
					                					<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapse_{{ $reference["id"] }}" aria-expanded="false" aria-controls="collapse_{{ $reference["id"] }}" >
						                					<i class="fa fa-chevron-down" aria-hidden="true"></i>
						                				</button>
					                				@endif
				                				@else
				                					{{ $component->part_name }}
				                				@endif 
				                			</td>
			                				<td>{{ $component->producer ? $component->producer->display_name : 'noname'}}</td>
			                				<td>
			                					@if ($reference["xquery"])
				                					<b>{{ $replacement->part_name }}</b>
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
			                			@if (!empty($reference->subreferences))
				                			
				                			@foreach ($reference->subreferences as $subreference)
				                				@php
							                    	$subcomponent = $subreference->component;
							                    	$subreplacement = $subreference->replacement;
						                    	@endphp
					                			<tr class="collapse" id="collapse_{{ $reference["id"] }}">                		
													
													<td>{{ $subreference->component->part_name }}&nbsp
					                					@if (!empty($subreference->subreferences))
						                					<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapse_{{ $subreference["id"] }}" aria-expanded="false" aria-controls="collapse_{{ $subreference["id"] }}" >
							                					<i class="fa fa-chevron-down" aria-hidden="true"></i>
							                				</button>
						                				@endif
													</td>
					                				<td>{{ $subcomponent->producer ? $subcomponent->producer->display_name : 'noname'}}</td>
					                				<td>{{ $subreference->replacement->part_name }}</td>
					                				<td>{{ $subreplacement->producer ? $subreplacement->producer->display_name : 'noname'}}</td>
					                				<td>{{ $subreference->type }}</td>
					                				<td>{{ $subreference->featured ? 'Yes' : 'No'}}</td>
					                				<td>{{ $subreference->fullRating }}
					                					@if (!Auth::guest() && $subreference->noVoted)
						                					<a href="" data-id="{{ $subreference->id }}" class="btn btn-vote btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm">Vote</a>
						                				@endif
					                				</td>
						                			
					                			</tr>
					                			
					                			@if (!empty($subreference->subreferences))
				                			
						                			@foreach ($subreference->subreferences as $subreference2)
						                				@php
						                					
									                    	$subcomponent = $subreference2->component;
									                    	$subreplacement = $subreference2->replacement;
								                    	@endphp
							                			<tr class="collapse" id="collapse_{{ $subreference["id"] }}">                		
															
															<td>{{ $subreference2->component->part_name }}&nbsp
							                					@if (!empty($subreference2->subreferences))
								                					<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapse_{{ $subreference["id"] }}" aria-expanded="false" aria-controls="collapse_{{ $subreference["id"] }}" >
									                					<i class="fa fa-chevron-down" aria-hidden="true"></i>
									                				</button>
								                				@endif
															</td>
							                				<td>{{ $subcomponent->producer ? $subcomponent->producer->display_name : 'noname'}}</td>
							                				<td>{{ $subreference2->replacement->part_name }}</td>
							                				<td>{{ $subreplacement->producer ? $subreplacement->producer->display_name : 'noname'}}</td>
							                				<td>{{ $subreference2->type }}</td>
							                				<td>{{ $subreference2->featured ? 'Yes' : 'No'}}</td>
							                				<td>{{ $subreference2->fullRating }}
							                					@if (!Auth::guest() && $subreference2->noVoted)
								                					<a href="" data-id="{{ $subreference2->id }}" class="btn btn-vote btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm">Vote</a>
								                				@endif
							                				</td>
								                			
							                			</tr>
						                			@endforeach
					                			@endif
					                			
				                			@endforeach
			                			@endif
				                @endforeach
		                    </tbody>
		                    
		                </table>
	                
					@else
	                    
                    	<p>No results</p>                 	
                    	
                    @endif
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div><!-- end col-md-12 -->
        
        @if (!Auth::guest())
		    <div class="text-center">
		    	
		    	{!! Form::open(['method' => 'POST', 'action' => 'ReferencesController@downloadExcel', 'class' => 'form-inline form-button']) !!}
		    		<a href="/reference/create" class="btn btn-primary btn-lg">+ Add my XReference</a>
				    {{ csrf_field() }}
				    {!! Form::hidden('components', $referenceCollection->pluck('id')->toJson()) !!}
				    {!! Form::submit('Download result', ['class'=>'btn btn-default btn-lg']) !!}
				{!! Form::close() !!}
		    </div>
		@endif
		
    </div><!-- end row -->
</div><!-- end container -->

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
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					{!! Form::submit('Vote', ['class' => 'btn btn-primary']); !!}
				</div>
				
			{!! Form::close() !!}
		</div>
	</div>
</div>

@endsection
