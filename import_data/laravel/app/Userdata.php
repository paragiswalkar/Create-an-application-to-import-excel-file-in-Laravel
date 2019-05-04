<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Userdata extends Model
{
	protected $table = 'import_data';
	public $timestamps = false;
	
	public $fillable = ['id','name','dob','email'];
}