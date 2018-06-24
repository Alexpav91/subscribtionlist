@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">

            <!-- Subscriber Display -->

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Subscriber
                    </div>  
                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Mail</th>
                                <th>Recep field</th>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td class="table-text" style="min-width:70%"><div>{{ $sub->mail }}</div></td>
										<td class="table-text">
											<div class="col-sm-6" style="margin-top:4px">
											<form action="{{ url('updatesub/'.$sub->id) }}" method="POST" onsubmit="return ConfirmUpdate()">
												{{ csrf_field() }}
												 <select name="recep" style="width:60px" required>
													<option value="1" @if ($sub->recepField == 1) selected="selected" @endif>TO</option>
													<option value="2" @if ($sub->recepField == 2) selected="selected" @endif>CC</option>
													<option value="3" @if ($sub->recepField == 3) selected="selected" @endif>BCC</option>
												</select>
											
												</div></td>
												<!-- Update Button -->
												<td style="text-align:right">
													<button type="submit" class="btn btn-primary">
														<i class="fa fa-btn fa-undo"></i>Update
													</button>
												</td>
											</form>
										<td style="text-align:right">
										<!--delete Button -->
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
												function ConfirmUpdate(){
													return confirm('Update?');
												}
											</script>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

				<a href="{{ url('subscriberlist/'.$sub->idSubscribtion) }}" class="btn btn-default">
					<i class="fa fa-btn fa-arrow-circle-left"></i>Back
				</a>

        </div>
    </div>
@endsection