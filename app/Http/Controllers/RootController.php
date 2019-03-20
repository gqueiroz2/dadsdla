<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;

class RootController extends Controller
{
    public function home(){

        $db = new dataBase();

        $con = $db->openConnection("root");

        $sql = "SELECT * FROM teste";

        $res = $con->query($sql);       

        return view("welcome");

    }
}
