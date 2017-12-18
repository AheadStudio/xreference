@extends('layouts.app')
	
@section('content')
<h1>Upload my database</h1>
<br />
<div class="row">
	<div class="col-md-12">	                    
	    @if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				{{ Session::get('success') }}
			</div>
		@endif
	    @if ($message = Session::get('error'))
			<div class="alert alert-danger" role="alert">
				{{ Session::get('error') }}
			</div>
		@endif
	    
	    @include('includes.form_error')
      
        {!! Form::open(['method' => 'POST', 'action' => ['ReferencesController@import'], 'files' => true, 'class' => 'form-horizontal']) !!}
            {{ csrf_field() }}	
			<div class="form-group">
				<div class="form-row">
	                <div class="col-md-8">
		                <label class="custom-file align-middle">
		                	{!! Form::file('import_file', ['class'=>'custom-file-input', 'required']) !!}
							<span class="custom-file-control"></span>
						</label>
						{!! Form::submit('Upload', ['class'=>'btn btn-primary align-middle']) !!}
	                </div>
				</div>
			</div>
        {!! Form::close() !!}
        <div class="alert alert-secondary">
			<p>We accept <code>.xls</code> and <code>.xlsx</code> files with our special format. Uploaded file should contains these fields:</p>
			<p>
				<kbd>Part number <br /> &nbsp;Manufacturer <br /> &nbsp;Status <br /> &nbsp;Package <br /> &nbsp;Xreference part number <br /> &nbsp;Xreference manufacturer <br /> &nbsp;Xreference status <br /> &nbsp;Xreference package <br /> &nbsp;<span data-toggle="tooltip" title="Direct, Functional or Nearest"><ins>Replacement type</ins></span> <br /> &nbsp;Comment <br /> &nbsp;<span data-toggle="tooltip" title="DEL for deleting"><ins>Del</ins></span></kbd>
			</p>
			<p>You can download file <a href="/upload/upload_test.xls" download target="_blank" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> example</a></p></div>
        </div>
    </div>
</div>
@endsection