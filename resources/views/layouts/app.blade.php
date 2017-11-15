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
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                   <a class="navbar-brand navbar-brand--image" href="{{ url('/') }}">
                        <img alt="Brand" src="/images/logo.png">
                        
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a></li>
                            <li><a href="{{ route('register') }}"><i class="fa fa-user" aria-hidden="true"></i> Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Welcome {{ Auth::user()->name }}!<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                    	@if (Auth::user()->hasPermission('browse_admin'))
	                                    	<a href="/admin">
	                                            Admin Panel 
	                                        </a>
                                    	
                                    	@endif
                                    	<a href="/account">
                                            My account
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
										
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        
        @yield('content')
    </div>
	
	<div class="container">
	    <div class="row">
	    	
	    </div>
	    
	    <hr>
	    
	    <div class="row">
	    
		    <div class="col-md-6"><p>© Xreference 2017</p></div>
		    
		    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-review-modal">Send feedback</a>
		    <a href="https://facebook.com" target="_blank" class="footer-social pull-right"><i class="fa fa-facebook" aria-hidden="true"></i></a>
		</div>
    </div>
    
    <div class="modal fade bs-review-modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				
				{!! Form::open(['method' => 'POST', 'action'=> 'HomeController@feedback', 'class' => 'form-horizontal']) !!}
		        	
		        	<div class="modal-header text-center">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Your feedback</h4>
					</div>
		        	
		        	<div class="modal-body">
		        		{{ csrf_field() }}
		        		{!! Form::hidden('reference_id', null, ['class' => "rate-reference"]); !!}
		        		{!! Form::hidden('query', null); !!}
		        		
		        		<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
			                {!! Form::label('name', 'Name', ['class' => 'col-md-2 control-label']) !!}
			                <div class="col-sm-10">
			                	{!! Form::text('name', null, ['class'=>'form-control', 'required'])!!}
			                </div>
			            </div>
			            
			            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
			                {!! Form::label('email', 'E-mail', ['class' => 'col-md-2 control-label']) !!}
			                <div class="col-sm-10">
			                	{!! Form::email('email', null, ['class'=>'form-control', 'required'])!!}
			                </div>
			            </div>
			            
			            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
			                {!! Form::label('phone', 'Phone', ['class' => 'col-md-2 control-label']) !!}
			                <div class="col-sm-10">
			                	{!! Form::text('phone', null, ['class'=>'form-control'])!!}
			                </div>
			            </div>
			            
			            <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
			                {!! Form::label('comment', 'Comment', ['class' => 'col-md-2 control-label']) !!}
			                <div class="col-sm-10">
			                	{!! Form::textarea('comment', null, ['class'=>'form-control', 'required'])!!}
			                </div>
			            </div>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
