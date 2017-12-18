<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-light bg-light">
		<div class="container">
	        <a class="navbar-brand" href="{{ url('/') }}">
		        <img alt="xReferences" src="/images/logo.png">
	        </a>
            <ul class="nav float-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="fa fa-user" aria-hidden="true"></i> Register</a></li>
                @else
                    <li class="nav-item">
                    	<div class="dropdown">
	                        <a href="#" class="nav-link dropdown-toggle" id="headerProfileMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                            Welcome {{ Auth::user()->name }}!<span class="caret"></span>
	                        </a>
	                        <div class="dropdown-menu" aria-labelledby="headerProfileMenu">
	                        	@if(Auth::user()->hasPermission('browse_admin'))
	                            	<a class="dropdown-item" href="/admin">
	                                    Admin Panel 
	                                </a>
	                        	@endif
	                        	<a class="dropdown-item" href="/account">
	                                My account
	                            </a>
	                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
	                                Logout
	                            </a>
	                        </div>
                    	</div>
                    </li>
                @endif
                
				@if (!Auth::guest())
				    <li class="nav-item">
				    	<a href="/reference/create" class="btn btn-success">+ Add my XReference</a>
				    </li>
				    &nbsp;
				@endif
                
                <li class="nav-item">
                	<a href="" class="btn btn-primary" data-toggle="modal" data-target=".bs-review-modal">Send feedback</a>
                </li>
            </ul>
		</div>
    </nav>
	
	<main class="page-content">
		<div class="container">
	       @yield('content')
		</div>
	</main>
	
	<footer class="footer page-footer">
		<div class="container">
		    <div class="float-left">© XReferences, {{ date("Y") }}</div>
		    <a href="https://www.facebook.com/xreferences.info/" target="_blank" class="float-right"><i class="fa fa-facebook" aria-hidden="true"></i></a>
		</div>
    </footer>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
	</form>

    <div class="modal fade bs-review-modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				
				{!! Form::open(['method' => 'POST', 'action'=> 'HomeController@feedback', 'class' => 'form-horizontal']) !!}
		        	
		        	<div class="modal-header">
			        	<h4 class="modal-title">Send feedback</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
		        	
		        	<div class="modal-body">
		        		{{ csrf_field() }}
		        		{!! Form::hidden('reference_id', null, ['class' => "rate-reference"]); !!}
		        		{!! Form::hidden('query', null); !!}
		        		
		        		<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
			                {!! Form::label('name', 'Name', ['class' => 'required']) !!}
			                {!! Form::text('name', null, ['class'=>'form-control', 'required'])!!}
			            </div>
			            
			            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
			                {!! Form::label('email', 'E-mail', ['class' => 'required']) !!}
			                {!! Form::email('email', null, ['class'=>'form-control', 'required'])!!}
			            </div>
			            
			            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
			                {!! Form::label('phone', 'Phone', ['class' => '']) !!}
			                {!! Form::text('phone', null, ['class'=>'form-control'])!!}
			            </div>
			            
			            <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
			                {!! Form::label('comment', 'Comment', ['class' => 'required']) !!}
			                {!! Form::textarea('comment', null, ['class'=>'form-control', 'required'])!!}
			            </div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						{!! Form::submit('Send', ['class' => 'btn btn-primary']); !!}
					</div>
					
				{!! Form::close() !!}
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
    

	<link rel="stylesheet" href="{{ asset('js/libs/bar-rating/themes/fontawesome-stars.css') }}">
    <!-- Scripts -->
    
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/libs/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/libs/bar-rating/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('js/xreference.js') }}"></script>
    
</body>
</html>
