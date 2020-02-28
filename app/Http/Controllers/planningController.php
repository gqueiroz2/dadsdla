<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class planningController extends Controller{
    
	public function home(){
		return view('planning.home');
	}

}
