@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
			<div class="page-header">
			<h3>Unsubscribe</h3>
			</div>
			<div class="panel-body">
			
			<!-- Display Validation Errors -->
			@include('common.errors')
				<!-- Display status -->
				@if ($status) 
					You successfully unsubscribed!
				@else 
					Your e-mail is not on the subscribtion list.
				@endif
			</div>
        </div>
    </div>
@endsection