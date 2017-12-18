@extends('layouts.app')

@section('content')

<div class="jumbotron text-center">
	<div class="container">
        <h1 class="jumbotron-heading">This project is intended for those who work with electronic components.</h1>
        <p class="lead text-muted">In the electronic world there are many functional and direct references. Our goal is to simplify their search and your life. You can add references yourself and improve our service for you.</p>
        <p class="lead text-muted">
	        There are
			<span class="badge badge-pill badge-lg badge-success badge-lg">{{ $count }}</span>
	        references in our database.
	    </p>
	</div>
	
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-md-10">
			    {!! Form::open(['method'=>'GET', 'action'=> 'HomeController@result', 'files'=>false, 'class' => 'form-horizontal']) !!}
					
					<div class="form-group">
				    	<div class="form-row">
				            <div class="col-md-10">
				            	{!! Form::text('part_name', null, ['class'=>'form-control form-control-lg', 'placeholder'=>'Searching request, minimum 3 symbols', 'pattern'=>'.{3,}', 'required']); !!}
				            </div>
							{!! Form::submit('Search', ['class' => 'col-md-2 btn-success']); !!}
				        </div>
					</div>
			        
			    	@if (!Auth::guest())
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
			        @endif
			
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

<div class="alert alert-light" role="alert">
	<p><i>On our website, all information is provided by users and requires verification before use. All decisions taken about the use of a replacement are taken by you alone and we are not responsible for them.</i></p>
</div>

@endsection
