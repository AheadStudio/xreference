@extends('layouts.app')

@section('content')
<h1>Reset password</h1>
<br />
<div class="row">
	<div class="col-md-6">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="required">E-Mail Address</label>

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="text-danger">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                
            </div>

            <div class="form-group">
                
                    <button type="submit" class="btn btn-primary btn-lg">
                        Send Password Reset Link
                    </button>
               
            </div>
        </form>
    </div>
</div>
@endsection
