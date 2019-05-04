<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Userdata;
class FileController extends Controller {
    public function importFileIntoDB(Request $request){
        if($request->hasFile('sample_file')){
            $path = $request->file('sample_file')->getRealPath();
            $data = \Excel::load($path)->get();
            if($data->count()){
                foreach ($data as $key => $value) {
                    $arr[] = ['name' => $value->name, 'dob' => date('Y-m-d', strtotime($value->dob)), 'email' => $value->email];
                }
                if(!empty($arr)){
                    \DB::table('import_data')->insert($arr);
                   return redirect()->back()->with('message', 'Insert Record successfully.');
                }
            }
        }
        dd('Request data does not have any files to import.');      
    }
	public function ListOfUsers(){
		$get_data = \DB::table('import_data')->get();
        $import_data = collect($get_data)->toArray();
		//echo "<pre>";print_r($import_data);exit;
		return view('listofusers', ['import_data'=>$import_data]);
    }	
    public function downloadExcelFile($type){
		$get_data = \DB::table('import_data')->get();
        $import_data = collect($get_data)->toArray();
		return \Excel::create('expertphp_demo', function($excel) use ($import_data) {
            $excel->sheet('sheet name', function($sheet) use ($import_data)
            {
                $sheet->fromArray($import_data);
            });
        })->download($type);
    }      
}