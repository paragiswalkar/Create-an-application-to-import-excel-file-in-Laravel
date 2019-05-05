<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Userdata;
use Session;
use File;
class FileController extends Controller {
    public function importFileIntoDB(Request $request){
		//validate the xls file
		 $this->validate($request, array(
		   'sample_file'      => 'required'
		 ));
        if($request->hasFile('sample_file')){
			$extension = File::extension($request->file('sample_file')->getClientOriginalName());
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
				$path = $request->file('sample_file')->getRealPath();
				$data = \Excel::load($path)->get();
				if($data->count()){
					foreach ($data as $key => $value) {
						$arr[] = ['name' => $value->name, 'dob' => date('Y-m-d', strtotime($value->dob)), 'email' => $value->email];
					}
					if(!empty($arr)){
						try{
							// insert the entry
							//$insertData = \DB::table('import_data')->insert($arr);
							foreach($arr as $key=>$val) {
								$insertData = Userdata::updateOrCreate([
        'email' => $val['email']
    ],['name' => $val['name'], 'dob' => $val['dob'], 'email' => $val['email']]);
							}
							
							if ($insertData) {
								Session::flash('success', 'Your Data has successfully imported');
							}else {                        
							   Session::flash('error', 'Error inserting the data..');
							   return back();
							}
						 } catch (QueryException $e) {
								if ($this->isDuplicateEntryException($e)) {
									throw new DuplicateEntryException('Duplicate Entry');
								}
								throw $e;
						}
					}
				}
				
				return back();
			} else {
				Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
				return back();
			}
        }
       // dd('Request data does not have any files to import.');      
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
	private function isDuplicateEntryException(QueryException $e)
	{
		$sqlState = $e->errorInfo[0];
		$errorCode  = $e->errorInfo[1];
		if ($sqlState === "23000" && $errorCode === 1062) {
			return true;
		}
		return false;
	}	
}