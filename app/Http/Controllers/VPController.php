<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
use App\VPPAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\VP;
use App\sql;
use App\base;
use App\brand;
use App\excel;
use Validator;

class VPController extends Controller{
   

    public function get(){

    	$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $user = Request::session()->get('userName');
       //var_dump($user);
        $months = array(intval(date('n'))+1,intval(date('n')) + 2,intval(date('n')) + 3);    

		return view('pAndR.VPView.get',compact('render','months','user'));
    }

    public function post(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new VPPAndRRender();
        $pr = new pRate();
        $vp = new vp();
        $sql = new sql();
        $base = new base();

        $cYear = date('Y');
        $pYear = $cYear - 1;
        $regionID = 1;
        $months = array(intval(date('n'))+1,intval(date('n')) + 2,intval(date('n')) + 3);    
        $user = Request::session()->get('userName');
        $month = Request::get('month');
        $monthName = $base->intToMonth2(array($month)); 
        $manager = Request::get('director');
        //var_dump($manager);
        if ($manager[0] == 'FM') {
            $managerName = 'Fabio Morgado';
        }elseif ($manager[0] == 'BP') {
            $managerName = 'Bruno Paula';
        }elseif ($manager[0] == 'RA') {
            $managerName = 'Ricardo Alves';
        }elseif($manager[0] == 'VV') {
            $managerName = 'Victor Vasconcelos';
        }else{
            $managerName = 'Regionais';
        }

        $date = date('Y-m-d');
        $fcstMonth = date('m');
        //var_dump(Request::all());
        $repsTable = $vp->repTable($con,$manager,$month,$cYear,$pYear);

        $managerTable = $vp->managerTable($con,$manager,$month,$cYear,$pYear,$repsTable);    
        //var_dump($repsTable);
        return view('pAndR.VPView.post',compact('base','render','months','cYear','pYear','repsTable','managerTable','managerName','monthName','user'));
    }
}
