@extends('layouts.app')
	
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Account</div>

                <div class="panel-body">
                    
                    <?php //dd($user); ?>
                    
                    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@update', $user->id], 'files' => true, 'class' => 'form-horizontal']) !!}
                        {{ csrf_field() }}
						
						
						<div class="form-group">
			                {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label deactivated']) !!}
			                <div class="col-md-6">
			                	{!! Form::text('name', null, ['class'=>'form-control'])!!}
			                </div>
			            </div>
						
                        <div class="form-group">
			                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label deactivated']) !!}
			                <div class="col-md-6">
			                	{!! Form::text('email', null, ['class'=>'form-control'])!!}
			                </div>
			            </div>	            
						
						<div class="form-group">
							{!! Form::label('is_company', 'Company:', ['class' => 'col-md-4 control-label deactivated']) !!}
							<div class="col-md-6">
			                	{!! Form::checkbox('is_company', null, false, ['class' => 'deactivated']); !!}
							</div>
			            </div>
						
						<div id="hiddenField" class="form-group tempHidden">
							<div class="col-md-6 col-md-offset-4 ">
			                	{!! Form::text('company_name', null, ['class'=>'form-control'])!!}
							</div>
			            </div>
						
						<div class="form-group">
			                {!! Form::label('password', 'Password', ['class' => 'col-md-4 control-label']) !!}
			                <div class="col-md-6">
			                	{!! Form::password('password', ['class'=>'form-control'])!!}
			                </div>
			            </div>
						
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
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
		    
		    @include('includes.form_error')
        </div>
    </div>
</div>

@endsection