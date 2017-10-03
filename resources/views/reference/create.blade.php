@extends('layouts.app')
	
@section('content')
	
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                
                <div class="panel-heading">Add new XReference</div>

                <div class="panel-body">
			        {!! Form::open(['method'=>'POST', 'action'=> 'ReferencesController@store', 'files'=>true, 'class' => 'form-horizontal']) !!}
			        	
			        	{!! Form::hidden('component_id', null, ['id' => 'hidden_comp_id'])!!}
			        	
			        	<div class="form-group">
			                {!! Form::label('component_name', 'Component Part Number', ['class' => 'col-md-3 control-label']) !!}
			                <div class="col-md-3">
			                	{!! Form::text('component_name', null, ['class'=>'form-control', 'required'])!!}
			                </div>
			            
			                {!! Form::label('component_producer', 'Component Producer', ['class' => 'col-md-3 control-label']) !!}
			                <div class="col-md-3">
			                	{!! Form::text('component_producer', null, ['class'=>'form-control', 'name'=>'component', 'id'=>'autocomplete', 'required'])!!}
			                </div>
			            </div>
			            
			            <div class="form-group">
			                {!! Form::label('ref_component_id', 'Reference Part Number', ['class' => 'col-md-3 control-label']) !!}
			                <div class="col-md-3">
			                	{!! Form::text('ref_component_id', null, ['class'=>'form-control', 'required'])!!}
			                </div>
			            
			                {!! Form::label('ref_producer', 'Reference Producer', ['class' => 'col-md-3 control-label']) !!}
			                <div class="col-md-3">
			                	{!! Form::text('ref_producer', null, ['class'=>'form-control', 'required'])!!}
			                </div>
			            </div>
			        	
			        	<div class="form-group">
			        		{!! Form::label('ref_component_id', 'Type', ['class' => 'col-md-3 col-md-offset-1 control-label']) !!}
			                <div class="col-md-4">
			                	{!! Form::select('ref_component_id', ['direct' => 'Direct', 'nearest' => 'Nearest', 'func_repl' => 'Functional Replacement'], null, ['class'=>'form-control'])!!}
			                </div>
			            </div>
			            
			            <div class="form-group">
			            	{!! Form::label('ref_component_id', 'Status', ['class' => 'col-md-3 col-md-offset-1 control-label']) !!}
			                <div class="col-md-4">
			                	{!! Form::select('ref_component_id', ['active' => 'Active', 'eol' => 'EOL', 'obsolete' => 'Obsolete', 'nrnd' => 'NRND'], null, ['class'=>'form-control'])!!}
			                </div>
			            </div>
			        	
						<div class="form-group">
                            <div class="col-md-6 col-md-offset-5">
                                <button type="submit" class="btn btn-primary">
                                    Add XReference
                                </button>
                            </div>
                        </div>
					
					{!! Form::close() !!}
                </div>
                
            </div>
	    </div>
	</div>
</div>

@endsection