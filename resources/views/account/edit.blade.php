@extends('layouts.app')
	
@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="card">
           	<div class="card-header"><h2>Account info</h2></div>
			<div class="card-body">
			    @if(Session::has('updated_user'))
					<div class="alert alert-info">{{session('updated_user')}}</div>
			    @endif
			    @include('includes.form_error')
                {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@update', $user->id], 'files' => true, 'class' => 'form-horizontal']) !!}
                    {{ csrf_field() }}
					<div class="form-group">
		                {!! Form::label('name', 'Name', ['class' => 'required']) !!}
		                {!! Form::text('name', null, ['class'=>'form-control'])!!}
		            </div>
					<div class="form-group">
		                {!! Form::label('email', 'E-mail', ['class' => 'required']) !!}
		                {!! Form::text('email', null, ['class'=>'form-control', 'readonly'])!!}
		            </div>	            
					<div class="form-group">
		                {!! Form::label('password', 'Password', ['class' => 'required']) !!}
		                {!! Form::password('password', ['class'=>'form-control'])!!}
		            </div>
					<div class="form-group">
						<div class="form-row align-items-center">
							<div class="col-auto">
								<div class="form-check mb-2 mb-sm-0">
									<label class="form-check-label">
							        	{!! Form::checkbox('is_company', null, false, ['class' => 'form-check-input deactivated']); !!}
							        	Company?
									</label>
								</div>
							</div>
							<div class="col-auto">
								{!! Form::text('company_name', null, ['placeholder' => 'Company name', 'class'=>'form-control mb-2 mb-sm-0', 'style' => 'visibility: hidden;'])!!}
							</div>
						</div>
				    </div>
					<div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">
                                Save
                            </button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
	</div>
	<div class="col-md-6">
		<div class="card">
           	<div class="card-header"><h2>My database</h2></div>
			<div class="card-body">
                <a class="btn btn-success btn-lg" href="/account/upload" role="button"><i class="fa fa-plus-square" aria-hidden="true"></i> Upload new</a>
                <br />
                <br />
                {!! Form::open(['method' => 'POST', 'action' => 'ReferencesController@downloadExcel', 'class' => 'form-horizontal form-button']) !!}
				    {{ csrf_field() }}
				    {!! Form::hidden('user', $user->id) !!}
				    {!! Form::submit('Download my database', ['class'=>'btn btn-primary btn-lg']) !!}
				{!! Form::close() !!}
			</div>
        </div>
    </div>
</div>
@endsection