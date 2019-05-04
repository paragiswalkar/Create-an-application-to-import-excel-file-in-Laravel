<?php
use App\Userdata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::auth();

Route::get('/', 'HomeController@index');

Route::get('show-users', 'FileController@ListOfUsers');
Route::get('import-export-csv-excel',array('as'=>'excel.import','uses'=>'FileController@importExportExcelORCSV'));
Route::post('import-csv-excel',array('as'=>'import-csv-excel','uses'=>'FileController@importFileIntoDB'));
Route::get('download-excel-file/{type}', array('as'=>'excel-file','uses'=>'FileController@downloadExcelFile'));

Route::post ( '/editdata', function (Request $request) {
	
	$rules = array (
			'name' => 'required',
			'dob' => 'required',
			'email' => 'required|email' 
	);
	$validator = Validator::make ( Input::all (), $rules );
	if ($validator->fails ())
		return Response::json ( array (
				
				'errors' => $validator->getMessageBag ()->toArray () 
		) );
	else {
		
		$data = Userdata::find ( $request->id );
		$data->name = ($request->name);
		$data->dob = ($request->dob);
		$data->email = ($request->email);
		$data->save ();
		return response ()->json ( $data );
	}
} );
Route::post ( '/deletedata', function (Request $request) {
	Data::find ( $request->id )->delete ();
	return response ()->json ();
} );
