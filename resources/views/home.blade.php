@extends('layouts.app')
	
@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="well hero-text">This project is intended for those who work with electronic components. In the electronic world there are many functional and direct references. Our goal is to simplify their search and your life. You can add references yourself and improve our service for you.</div>
            
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
				                {!! Form::hidden('user_id', $userId) !!}
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
	    
	    @if (!Auth::guest())
		    <div class="text-center">
		    	<a href="/reference/create" class="btn btn-primary btn-lg">+ Add my XReference</a>
		    </div>
		@endif
	</div>
	
	@if(Session::has('reference_created'))
		<div class="row">
			<br>
			<div class="col-md-10 col-md-offset-1 alert alert-warning">
		        <p>{{session('reference_created')}}</p>
			</div>
		</div>
    @endif
</div>

@endsection