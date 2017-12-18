@extends('layouts.app')

@section('content')
<h1>Reset password</h1>
<br />
<div class="row">
	<div class="col-md-6">
        <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="required">E-mail address</label>

                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

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

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="required">Confirm password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger">
                            {{ $errors->first('password_confirmation') }}
                        </span>
                    @endif
               
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">
                        Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
