@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-12">
						<a href="{{ url('show-users') }}">List of Users</a>
					  </div>
				   </div>
					<form action="{{url('import-csv-excel')}}" method="post" enctype="multipart/form-data">
						<div class="row">
						   <div class="col-xs-12 col-sm-12 col-md-12">
								<div class="form-group">
									<label class="col-md-3">Select File to Import:</label>
									<div class="col-md-9">
									{{csrf_field()}}
									<input type="file" id="sample_file" name="sample_file" class="form-control"/>
									@if(session()->has('message'))
										<div class="alert alert-success">
											{{ session()->get('message') }}
										</div>
									@endif
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 text-center">
							<input type="submit" class="btn btn-primary">
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
