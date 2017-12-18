@extends('layouts.app')

@section('content')
<h1>Register</h1>
<br />
<div class="row">
	<div class="col-md-6">              
		<form class="form" method="POST" action="{{ route('register') }}">
		    {{ csrf_field() }}
		
		    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
		        <label for="name" class="required">Name</label>
		
		        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
		
		            @if ($errors->has('name'))
		                <span class="text-danger">
		                    {{ $errors->first('name') }}
		                </span>
		            @endif
		        
		    </div>
		
		    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		        <label for="email" class="required">E-Mail Address</label>
		
		        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
		
		            @if ($errors->has('email'))
		                <span class="text-danger">
		                    {{ $errors->first('email') }}
		                </span>
		            @endif
		       
		    </div>
		
		    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		        <label for="password" class="required">Password</label>
		
		            <input id="password" type="password" class="form-control" name="password" required>
		
		            @if ($errors->has('password'))
		                <span class="text-danger">
		                    {{ $errors->first('password') }}
		                </span>
		            @endif
		        
		    </div>
		
		    <div class="form-group">
		        <label for="password-confirm" class="required">Confirm Password</label>
		
		            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
		       
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
				{!! app('captcha')->display($attributes = []) !!}
			</div>
			
		    <div class="form-group">
		            <button type="submit" class="btn btn-primary btn-lg">
		                Register
		            </button>
		    </div>
		</form>
	</div>
</div>
@endsection
