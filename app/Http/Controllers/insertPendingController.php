<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class insertPendingController extends Controller{
    
	public function insertClientUnit(){
		var_dump(Request::all());
		var_dump("oie");
	}

}
