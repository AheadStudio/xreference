@extends('layouts.app')
	
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Upload my database</div>
				<div class="panel-body">
                    <div class="col-md-10 col-md-offset-1">
	                    
	                    {!! Form::open(['method' => 'POST', 'action' => ['ReferencesController@import'], 'files' => true, 'class' => 'form-horizontal']) !!}
	                        {{ csrf_field() }}
							
							<div class="form-group">
				                <div class="col-md-5">
				                	{!! Form::file('import_file', ['class'=>'form-control', 'required']) !!}
				                </div>
	
							
				                <div class="col-md-4 ">
	                                {!! Form::submit('Upload', ['class'=>'btn btn-primary']) !!}
	                            </div>
	                            
	                            
				            </div>
	                    {!! Form::close() !!}
	                    <hr>
						<p class="">We accept .xls and .xlsx files <i class="fa fa-file-excel-o" aria-hidden="true"></i> with our format:<br><kbd>part number | manufacturer | xreference part number | <span data-toggle="tooltip" title="Direct, Functional or Nearest"><ins>replacement type</ins></span></kbd><br><br>You can download file <a href="#"><i class="fa fa-download" aria-hidden="true"></i> example</a>.</p>
                    	
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
		    @if(Session::has('updated_user'))
				<div class="alert alert-warning">
			        <p>{{session('updated_user')}}</p>
				</div>
		    @endif
		    
		    @if ($message = Session::get('error'))
				<div class="alert alert-danger" role="alert">
					{{ Session::get('error') }}
				</div>
			@endif
		    
		    @include('includes.form_error')
        </div>
    </div>
    
</div>

@endsection