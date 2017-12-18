@extends('layouts.app')
	
@section('content')
<h1>Add new XReference</h1>
@if(Session::has('reference_create_message'))
	<div class="alert alert-success">
	    <p>{{session('reference_create_message')}}</p>
	</div>
@endif 
{!! Form::open(['method'=>'POST', 'action'=> 'ReferencesController@store', 'files'=>true, 'class' => 'form-horizontal']) !!}
	
	{!! Form::hidden('component_id', null, ['id' => 'hidden_comp_id'])!!}
	{!! Form::hidden('ref_component_id', null, ['id' => 'hidden_ref_id'])!!}
	
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">Component</div>
				<div class="card-body">
			    	<div class="form-group">
			    		{!! Form::label('part_number', 'Input part number', ['class' => 'required']) !!}
						{!! Form::text('part_number', null, ['class'=>'form-control', 'required'])!!}
			    	</div>
		        	<div class="form-group">
		                {!! Form::label('part_manufacturer', 'Input manufacturer', ['class' => 'required']) !!}
		                {!! Form::text('part_manufacturer', null, ['class'=>'form-control', 'required'])!!}
		        	</div>
		        	<div class="form-group">
			        	{!! Form::label('part_status', 'Input component status', ['class' => '']) !!}
			        	{!! Form::select('part_status', ['preliminary' => 'Preliminary', 'active' => 'Active', 'end_of_life' => 'End of life', 'obsolete' => 'Obsolete'], null, ['class'=>'form-control'])!!}
		        	</div>
		        	<div class="form-group">
		                {!! Form::label('part_package', 'Input component Package/Case', ['class' => '']) !!}
		                {!! Form::text('part_package', null, ['class'=>'form-control'])!!}
		        	</div>
				</div>
			</div>
    	</div>
    	
    	<div class="col-md-6">
			<div class="card">
				<div class="card-header">Reference</div>
				<div class="card-body">
		        	<div class="form-group">
		        		{!! Form::label('xreference_number', 'Input xreference part number', ['class' => 'required']) !!}
		        		{!! Form::text('xreference_number', null, ['class'=>'form-control', 'required'])!!}
		        	</div>        	
		        	<div class="form-group">
		                {!! Form::label('xreference_manufacturer', 'Input xreference manufacturer', ['class' => 'required']) !!}
		                {!! Form::text('xreference_manufacturer', null, ['class'=>'form-control', 'required'])!!}
		        	</div>        	
		        	<div class="form-group">
		                {!! Form::label('xreference_status', 'Input xreference status', ['class' => '']) !!}
		                {!! Form::select('xreference_status', ['preliminary' => 'Preliminary', 'active' => 'Active', 'func_repl' => 'End of life', 'obsolete' => 'Obsolete'], null, ['class'=>'form-control'])!!}
		        	</div>
		        	<div class="form-group">
		                {!! Form::label('xreference_package', 'Input xreference Package/Case', ['class' => '']) !!}
		                {!! Form::text('xreference_package', null, ['class'=>'form-control'])!!}
		        	</div>
    			</div>
			</div>
    	</div>
	</div>
	<br />
	<div class="row justify-content-md-center">
        <div class="col-md-7">
        	<div class="form-group">
	        	{!! Form::label('comment', 'Input comment', ['class' => '']) !!}
	        	{!! Form::textarea('comment', null, ['class'=>'form-control', 'rows' => '5'])!!}
        	</div>
			<div class="form-group">
				{!! Form::label('type', 'Type', ['class' => '']) !!}
		        {!! Form::select('type', ['direct' => 'Direct', 'nearest' => 'Nearest', 'func_repl' => 'Functional Replacement'], null, ['class'=>'form-control'])!!}
		    </div>
        </div>
	</div>
	<br />
	<div class="row justify-content-md-center">
		<div class="form-group">
	        <div class="col-md-6 col-md-offset-5">
	            <button type="submit" class="btn btn-primary btn-lg" id="submit-reference">
	                Add XReference
	            </button>
	        </div>
	    </div>
	</div>

	<!--
	<div class="form-group">
        {!! Form::label('component_name', 'Component Part Number', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
        	{!! Form::text('component_name', null, ['class'=>'form-control', 'required'])!!}
        </div>
    
        {!! Form::label('reference_name', 'Reference Part Number', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
        	{!! Form::text('reference_name', null, ['class'=>'form-control', 'required'])!!}
        </div>
    </div>
    -->
	

    
    <!--<div class="col-md-6 col-md-offset-3">
        <div class="alert alert-warning alert-dismissible" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <strong>Warning!</strong> You should pick components from the dropdown
		</div>
    </div>-->

{!! Form::close() !!}
                

@endsection