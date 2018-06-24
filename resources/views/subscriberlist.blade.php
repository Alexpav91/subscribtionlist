@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
			<div class="page-header"><h3>{{ $list->name  }}@alerts.prime-intra.net</h3></div>
			<div class="panel-body">
			
			<!-- Display Validation Errors -->
			@include('common.errors')
			<!-- New Allowed Sender -->

			<div class="panel-body">


				<!-- New Allowed Sender Form -->
				<form action="{{ url('addsender')}}" method="POST" class="form-horizontal">
					{{ csrf_field() }}

					<!-- Subscribtion List Id -->

					<input type="hidden" name="idSubscribtion" id="sub-id" class="form-control" value="{{ $list->id  }}">

					<!-- Allowed Sender -->
					<div class="form-group">
						<label for="sender" class="col-sm-3 control-label">Allowed Sender</label>

						<div class="col-sm-6">
							<input type="text" name="sender" id="sender" class="form-control" value="{{ old('sender') }}">
						</div>
					</div> 

					<!-- Add Allowed Sender Button -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<button type="submit" class="btn btn-default">
								<i class="fa fa-btn fa-plus"></i>Add Allowed Sender
							</button>
						</div>
					</div>
				</form>
			</div>
			
			<!-- Allowed Sender Display -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Allowed Sender<b>
				</div>  
				<div class="panel-body">
					<table class="table table-striped task-table">
						<thead>
							<th>E-Mail</th>
						</thead>
						<tbody>
							@foreach($allowed as $sender)
								<tr>
									<td class="table-text" style="min-width:70%"><div>{{ $sender->sender }}</a></div></td>
									</td>
									<td style="text-align:right">
										<form action="{{ url('deletesender/'.$sender->id) }}" method="POST" onsubmit="return ConfirmDelete()">
											{{ csrf_field() }}
											{{ method_field('DELETE') }}

											<button type="submit" class="btn btn-danger">
												<i class="fa fa-btn fa-trash"></i>Delete
											</button>
										</form>

									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			
			
			 <!-- New Subscriber -->

			<div class="panel-body">

				<!-- New Subscriber Form -->
				<form action="{{ url('addsub')}}" method="POST" class="form-horizontal">
					{{ csrf_field() }}
					
					<!-- Subscribtion List Id -->
					 <div class="form-group">
						<label for="sub-id" class="col-sm-3 control-label">Subscribtion List ID</label>
						<div class="col-sm-6">
							<input type="text" name="idSubscribtion" id="sub-id" class="form-control" value="{{ $list->id  }}" readonly>
						</div>
					</div>

					<!-- Subscribtion Mail -->
					<div class="form-group">
						<label for="sub-mail" class="col-sm-3 control-label">E-Mail</label>

						<div class="col-sm-6">
							<input type="text" name="mail" id="sub-mail" class="form-control" value="{{ old('mail') }}">
						</div>
					</div>
					<!-- Subscribtion RecepField -->						
					<div class="form-group">
						<label for="sub-recep" class="col-sm-3 control-label">Reception Field</label>

						<div class="col-sm-6" style="margin-top:9px">
						 <!-- <input type="text" name="recep" id="sub-recep" class="form-control" value="{{ old('recep') }}"> -->
						 <select name="recep" style="width:60px" required>
							<option value="1">TO</option>
							<option value="2">CC</option>
							<option value="3" selected="selected">BCC</option>
						</select>
						</div>
					</div>

					<!-- Add Subscriber Button -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<table>
						<tr>
							<!-- Add Subscriber Button -->
							<td>

									<button type="submit" class="btn btn-default">
										<i class="fa fa-btn fa-plus"></i>Add Subscriber
									</button>
							
							</td>
							<td>
							<b>&nbsp;&nbsp;&nbsp;OR&nbsp;&nbsp;&nbsp;</b>

							</td>
							<td>
							<a href="/uploadsubs/{{ $list->id }}" class="btn btn-default">
								<i class="glyphicon glyphicon-upload"></i>&nbsp;&nbsp;Upload
							</a>


							</td>
						</tr>
					</table>
						</div>
					</div>
				</form>				
			</div>
		<!-- Subscriber Display -->

			<div class="panel panel-default">
				<div class="panel-heading">
					Subscriber
				</div>  
				<div class="panel-body">
					<table class="table table-striped">
						<thead>
							<th>E-Mail</th>
							<th>Recep field</th>
						</thead>
						<tbody>
							@foreach($subs as $sub)
								<tr>
									<td class="table-text" style="min-width:70%"><div><a href="{{ url('subscriber/'. $sub->id) }}">{{ $sub->mail }}</a></div></td>
									<td class="table-text"><div>
									@if ($sub->recepField == 1)
										TO
									@elseif ($sub->recepField == 2) 
										CC
									@elseif ($sub->recepField == 3)
										BCC
									@endif
									</div></td>
									<td style="text-align:right">
										<form action="{{ url('deletesub/'.$sub->id) }}" method="POST" onsubmit="return ConfirmDelete()">
											{{ csrf_field() }}
											{{ method_field('DELETE') }}

											<button type="submit" class="btn btn-danger">
												<i class="fa fa-btn fa-trash"></i>Delete
											</button>
										</form>
										<script>
											function ConfirmDelete(){
												return confirm('Are you sure?');
											}
										</script>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<a href="{{ url('subscribtion/') }}" class="btn btn-default">
				<i class="fa fa-btn fa-arrow-circle-left"></i>Back
			</a>
			</div>
        </div>
    </div>
@endsection