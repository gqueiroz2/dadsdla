<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;
use Validator;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\RenderAgencyClient;
use App\ClientAgency;
use App\base;
use App\region;
use App\agency;
use App\client;

class ClientAgencyController extends Controller{
    
	public function clientGet(){

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $region = $r->getRegion($con);
        $cli = new client();

        $clientGroup = $cli->getClientGroup($con);

        return view('dataManagement.clientGet',compact('region','clientGroup'));

    }

	public function agencyGet(){

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $region = $r->getRegion($con);
        $ag = new agency();

        $agencyGroup = $ag->getAgencyGroup($con);

        return view('dataManagement.AgencyClient.agencyGet',compact('region','agencyGroup'));

    }

    public function insertGroup(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $type = Request::get('type');
        $region = Request::get('region');
        $name = Request::get('groupName');

        $table = $type."_group";
        
        $insert = "INSERT INTO $table (region_id,name) VALUES ( \"".$region."\" , \"".$name."\" )";
        
        if($con->query($insert) === TRUE){
            return back()->with('insertedGroup',"The register was succesfully created on the table !!!"); 
        }else{
            return back()->with('failedGroup',"The register was not created on the table !!!");
        }
        
    }

    public function insertOne(){

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $type = Request::get('type');
        $groupName = Request::get('groupName');
        $name = Request::get('name');
        $table = $type;
        
        $insert = "INSERT INTO $table (".$type."_group_id,name) VALUES ( \"".$groupName."\" , \"".$name."\" )";

        if($con->query($insert) === TRUE){
            return back()->with('insertedTable',"The register was succesfully created on the table !!!"); 
        }else{
            return back()->with('failedTable',"The register was not created on the table !!!");
        }

    }

    public function rootExcel(){
    	$rAC = new RenderAgencyClient();
    	return view('dataManagement.AgencyClient.get',compact('rAC'));
    }

    public function excelHandler(){
    	
    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$cA = new ClientAgency();
		$base = new base();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		
		$year = Request::get('year');
		$type = Request::get('type');
		$fileNames = array('fileTypeGroup','fileType','fileTypeUnit');
		$table = $cA->handler($type,false);		
		for ($f=0; $f < sizeof($fileNames); $f++) { 
			$spreadSheet[$f] = $i->spread($fileNames[$f]);
			unset($spreadSheet[$f][0]);			
			$spreadSheet[$f] = array_values($spreadSheet[$f]);
		}
		
		$complete = $cA->toDataBase($con,$table,$spreadSheet,$base);
		if($complete){
            var_dump("FOI");
        }else{
            var_dump("NAO FOI");
        }
    }
}