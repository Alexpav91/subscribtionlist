@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
			<div class="page-header"><h3>{{ $list->name  }}@alerts.prime-intra.net</h3></div>
			<div class="panel-body">
			
			<!-- Display Validation Errors -->
			@include('common.errors')

			<div class="panel-body">

			<!-- Upload subs-->
				<form action="/uploadsubs/{{ $list->id }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
					{{ csrf_field() }}

					<!-- Subscribtion List Id -->

					<input type="hidden" name="idSubscribtion" id="sub-id" class="form-control" value="{{ $list->id  }}">

					<!-- Upload Excel -->
					<div class="form-group">
						<label for="import_file" class="col-sm-3 control-label">Upload Excel File</label>

						<div class="col-sm-6">
							<input type="file" name="import_file" id="import_file" class="form-control-file" enctype="multipart/form-data">
						</div>
					</div> 

					<!-- Upload Button -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<button type="submit" class="btn btn-default">
								<i class="glyphicon glyphicon-upload"></i>&nbsp;&nbsp;Upload
							</button>
						</div>
					</div>
				</form>
			</div>
			
			<div class="panel-body">
				Please format the Excel sheet as shown below. The first row <b>must</b> include "to", "cc" & "bcc".
				<br><br>
				 <img class="img-responsive" src="/img/excelformat.jpg" alt="Format" style="border: 5px solid; border-color: gray;"> 
				<br><br>
			</div>
			

			<a href="/subscriberlist/{{ $list->id }}" class="btn btn-default">
				<i class="fa fa-btn fa-arrow-circle-left"></i>Back
			</a>
			</div>
        </div>
    </div>
@endsection