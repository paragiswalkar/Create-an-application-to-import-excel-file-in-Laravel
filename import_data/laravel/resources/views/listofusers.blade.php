@extends('layouts.app')

@section('content')
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <!--<div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-12">
						<a href="{{ route('excel-file',['type'=>'xls']) }}">Download Excel xls</a> |
						<a href="{{ route('excel-file',['type'=>'xlsx']) }}">Download Excel xlsx</a> |
						<a href="{{ route('excel-file',['type'=>'csv']) }}">Download CSV</a>
					  </div>
				   </div> -->
					<div class="row">
					@if(count($import_data))
					<table class="table table-striped" id="table">
					  <thead>
						<tr>
						  <td>Name</td>
						  <td>DOB</td>
						  <td>Email</td>
						  <td>Action</td>
						</tr>
					  </thead>
					  @foreach($import_data as $item)
						<tr>
						  <td>{{$item->name}}</td>
						  <td>{{$item->dob}}</td>
						  <td>{{$item->email}}</td>
						  <td><button class="edit-modal btn btn-info"
									data-info="{{$item->id}},{{$item->name}},{{$item->dob}},{{$item->email}}">
									<span class="glyphicon glyphicon-edit"></span> Edit
								</button>
								<button class="delete-modal btn btn-danger"
									data-info="{{$item->id}},{{$item->name}},{{$item->dob}},{{$item->email}}">
									<span class="glyphicon glyphicon-trash"></span> Delete
								</button></td>
						</tr>
					  @endforeach
					</table>
					@endif
				  </div>
                </div>
            </div>
        </div>
    </div>
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>

				</div>
				<div class="modal-body">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label class="control-label col-sm-2" for="id">ID</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="id" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="name">Name</label>
							<div class="col-sm-10">
								<input type="name" class="form-control" id="name">
							</div>
						</div>
						<p class="name_error error text-center alert alert-danger hidden"></p>
						<div class="form-group">
							<label class="control-label col-sm-2" for="dob">DOB</label>
							<div class="col-sm-10">
								<input type="name" class="form-control" id="dob">
							</div>
						</div>
						<p class="dob_error error text-center alert alert-danger hidden"></p>
						<div class="form-group">
							<label class="control-label col-sm-2" for="email">Email</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="email">
							</div>
						</div>
						<p class="email_error error text-center alert alert-danger hidden"></p>
					</form>
					<div class="deleteContent">
						Are you Sure you want to delete <span class="dname"></span> ? <span
							class="hidden id"></span>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn actionBtn" data-dismiss="modal">
							<span id="footer_action_button" class='glyphicon'> </span>
						</button>
						<button type="button" class="btn btn-warning" data-dismiss="modal">
							<span class='glyphicon glyphicon-remove'></span> Close
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script>
  $(document).ready(function() {
    $('#table').DataTable();
	
	$(document).on('click', '.edit-modal', function() {
        $('#footer_action_button').text(" Update");
        $('#footer_action_button').addClass('glyphicon-check');
        $('#footer_action_button').removeClass('glyphicon-trash');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').removeClass('delete');
        $('.actionBtn').addClass('edit');
        $('.modal-title').text('Edit');
        $('.deleteContent').hide();
        $('.form-horizontal').show();
        var stuff = $(this).data('info').split(',');
        fillmodalData(stuff)
        $('#myModal').modal('show');
    });
	
	$(document).on('click', '.delete-modal', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').removeClass('edit');
        $('.actionBtn').addClass('delete');
        $('.modal-title').text('Delete');
        $('.deleteContent').show();
        $('.form-horizontal').hide();
        var stuff = $(this).data('info').split(',');
        $('.id').text(stuff[0]);
        $('.dname').html(stuff[1] +" "+stuff[2]);
        $('#myModal').modal('show');
    });
	
	$('.modal-footer').on('click', '.edit', function() {
        $.ajax({
            type: 'post',
            url: '/import_data/laravel/public/editdata',
            data: {
                '_token': "{{ csrf_token() }}",
                'id': $("#id").val(),
                'name': $('#name').val(),
                'dob': $('#dob').val(),
                'email': $('#email').val()
            },
            success: function(data) {
            	if (data.errors){
                	$('#myModal').modal('show');
                    if(data.errors.fname) {
                    	$('.name_error').removeClass('hidden');
                        $('.name_error').text("Name can't be empty !");
                    }
                    if(data.errors.lname) {
                    	$('.dob_error').removeClass('hidden');
                        $('.dob_error').text("dob can't be empty !");
                    }
                    if(data.errors.email) {
                    	$('.email_error').removeClass('hidden');
                        $('.email_error').text("Email must be a valid one !");
                    }
                }
            	 else {
            		 
                     $('.error').addClass('hidden');
                $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" +
                        data.id + "</td><td>" + data.name +
                        "</td><td>" + data.dob + "</td><td>" + data.email + "</td><td>" +
                          "</td><td><button class='edit-modal btn btn-info' data-info='" + data.id+","+data.name+","+data.dob+","+data.email+"'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-info='" + data.id+","+data.name+","+data.dob+","+data.email+"' ><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
            	}
				window.location.href = window.location.href;
			}
        });
    });
	
	$('.modal-footer').on('click', '.delete', function() {
        $.ajax({
            type: 'post',
            url: '/import_data/laravel/public/deletedata',
            data: {
                '_token': "{{ csrf_token() }}",
                'id': $('.id').text()
            },
            success: function(data) {
                $('.item' + $('.id').text()).remove();
            }
        });
    });
});

function fillmodalData(details){
    $('#id').val(details[0]);
    $('#name').val(details[1]);
    $('#dob').val(details[2]);
    $('#email').val(details[3]);
}
</script>