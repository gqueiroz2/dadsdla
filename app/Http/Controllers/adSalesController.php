<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\dataBase;
use App\ytd;
use Excel;

class adSalesController extends Controller{
    public function home(){
        

        return view("adSales.home");
    }
 
 	public function email(){
        

        return view("email");
    }

    public function import(){
        
    }
}
