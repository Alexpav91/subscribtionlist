@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
			<div class="page-header"><h3>Welcome {{ Auth::user()->name }}</h3></div>
			<div class="panel-heading">
				Add New Subscribtion
			</div>

			<div class="panel-body">
				<!-- Display Validation Errors -->
				@include('common.errors')

				<!-- New Subscribtion List Form -->
				<form action="{{ url('subscribtion')}}" method="POST" class="form-horizontal">
					{{ csrf_field() }}

					<!-- Subscribtion Name -->
					<div class="form-group">
						<label for="sub-name" class="col-sm-3 control-label">Name</label>

						<div class="col-sm-6">
							<input type="text" name="name" id="sub-name" class="form-control" value="{{ old('name') }}">
						</div>
					</div> 
					<!-- Subscribtion Describtion -->					
					<div class="form-group">
						<label for="sub-describtion" class="col-sm-3 control-label">Describtion</label>

						<div class="col-sm-6">
							<input type="text" name="describtion" id="sub-describtion" class="form-control" value="{{ old('describtion') }}">
						</div>
					</div>
					<!-- Subscribtion Owner -->
					<div class="form-group">
						<label for="sub-describtion" class="col-sm-3 control-label">Allowed Sender</label>

						<div class="col-sm-6">
							<input type="text" name="sender" id="sub-owner" class="form-control" value="{{ old('sender') }}">
						</div>
					</div>

					<!-- Add Subscribtion Button -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<button type="submit" class="btn btn-default">
								<i class="fa fa-btn fa-plus"></i>Add Subscribtion
							</button>
						</div>
					</div>
				</form>
			</div>
            <!-- Subscribtion Lists Display -->

			<div class="panel panel-default">
				<div class="panel-heading">
					Your Subscribtion Lists
				</div>

				<div class="panel-body">
					<table class="table table-striped task-table">
						<thead>
							<th>Name</th>
							<th>Describtion</th>
							<th class="text-right">Owner</th>
						</thead>
						<tbody>
							@foreach ($sublist as $list)
								<tr>
									<td class="table-text" style="min-width:28%"><div><a href="./subscriberlist/{{ $list->id }}">{{ $list->name }}</a></div></td>
									<td class="table-text" style="min-width:45%"><div>{{ $list->describtion }}</div></td>
									<td class="table-text" align="right"><div>{{ $list->owner }}</div></td>
									<!-- Task Delete Button -->
									<td style="text-align:right">
										<form action="{{ url('listdelete/'.$list->id) }}" method="POST" onsubmit="return ConfirmDelete()">
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
        </div>
    </div>
@endsection