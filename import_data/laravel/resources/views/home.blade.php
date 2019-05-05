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
								@if ( Session::has('success') )
									<div class="alert alert-success alert-dismissible" role="alert">
									  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									<strong>{{ Session::get('success') }}</strong>
								</div>
								@endif

								@if ( Session::has('error') )
								<div class="alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									<strong>{{ Session::get('error') }}</strong>
								</div>
								@endif

								@if (count($errors) > 0)
								<div class="alert alert-danger">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  <div>
									@foreach ($errors->all() as $error)
									<p>{{ $error }}</p>
									@endforeach
								</div>
								</div>
								@endif
								@foreach($errors->get('email') as $error)
									<span class="help-block">{{ $error }}</span>
								@endforeach	
								<div class="form-group">
									<label class="col-md-3">Select File to Import:</label>
									<div class="col-md-9">
									{{csrf_field()}}
									<input type="file" id="sample_file" name="sample_file" class="form-control"/>
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
