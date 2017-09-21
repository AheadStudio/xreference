@extends('layouts.app');
	
@section('content')

<div class="container">

	<h1>Edit Account</h1>
	
	
	{!! Form::open(['method' => 'POST', 'action' => 'UsersController@store', 'files' => true]) !!}
	
	<div class="form-group">
		{!! Form::label('name', 'Name:') !!}
		{!! Form::text('name', null, ['class' => 'form-control']) !!}
	</div>

</div>
@endsection