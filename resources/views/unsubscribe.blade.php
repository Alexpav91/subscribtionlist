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
				<!-- Unsubscribe -->
				<form action="{{ url()->current()}}" method="POST" class="form-horizontal">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
					<!-- Unsubscribe Address -->
					<div class="form-group">
						<label for="mail" class="col-sm-3 control-label">Your E-Mail Address</label>

						<div class="col-sm-6">
							<input type="text" name="mail" id="mail" class="form-control">
						</div>
					</div> 

					<!-- Add Allowed Sender Button -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<button type="submit" class="btn btn-default">
								<i class="glyphicon glyphicon-minus"></i>&nbsp;&nbsp;&nbsp;Unsubscribe
							</button>
						</div>
					</div>
				</form>
			</div>
        </div>
    </div>
@endsection