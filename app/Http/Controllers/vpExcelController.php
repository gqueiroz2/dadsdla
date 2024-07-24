<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\vpExport;

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

class vpExcelController extends Controller{
    

    public function vpView(){
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
        $cMonth = date('M');
        $cDate = date('d/m/Y');
        
        $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $cYear"));
        if ($cDate >= $lastMonday) {
            $currentMonth = strval(date('n'))+1; 
            $nextMonth = strval(date('n')+2);  
            $nextNMonth = strval(date('n')+3);  
            //var_dump($currentMonth);
            $currentMonthName = $base->intToMonth2(array(intval(date('n')+1))); 
            $nextMonthName = $base->intToMonth2(array(intval(date('n')+2)));  
            $nextNMonthName = $base->intToMonth2(array(intval(date('n')+3)));
        }else{
            $currentMonth = strval(date('n')); 
            $nextMonth = strval(date('n')+1);  
            $nextNMonth = strval(date('n')+2);  
            
            //var_dump($currentMonth);
            $currentMonthName = $base->intToMonth2(array(intval(date('n')))); 
            $nextMonthName = $base->intToMonth2(array(intval(date('n')+1)));  
            $nextNMonthName = $base->intToMonth2(array(intval(date('n')+2))); 
        }    
        $company = array('1','2','3');
        
        $user = json_decode(base64_decode(Request::session()->get('userName')));
        $month = Request::get('month');
        $monthName = $base->intToMonth2(array($month)); 
        $manager = json_decode(base64_decode(Request::get('manager')));
        $typeExport = Request::get('typeExport');

        $title = "Manager View.xlsx";
        $titleExcel = "Manager View.xlsx";
        $auxTitle = $title;

        $label = "exports.PandR.managerView.managerExport";        
        
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

        $repsTableC = $vp->repTable($con,$manager,$currentMonth,$cYear,$pYear);

        $repsTableN = $vp->repTable($con,$manager,$nextMonth,$cYear,$pYear);

        $repsTableNN = $vp->repTable($con,$manager,$nextNMonth,$cYear,$pYear);

        $managerTable = $vp->managerTable($con,$manager,$month,$cYear,$pYear,$repsTableC);  
        

        for ($c=0; $c < sizeof($company); $c++) { 
            if ($company[$c] == '1') {
                $color[$c] = '#0070c0';
                $companyView[$c] = 'DSC';
            }elseif ($company[$c] == '2') {
                $color[$c] = '#000000';
                $companyView[$c] = 'SPT';
            }elseif ($company[$c]) {
                $color[$c] = '#0f243e;';
                $companyView[$c] = 'WM';
            }
        }
        //var_dump($repsTable);

        $data = array('repsTableC' => $repsTableC, 'repsTableN' => $repsTableN, 'repsTableNN' => $repsTableNN, 'managerTable' => $managerTable, 'monthName' => $monthName, 'managerName' => $managerName, 'currentMonth' => $currentMonth, 'nextMonth' => $nextMonth, 'nextNmonth' => $nextNMonth, 'company' => $company,'companyView' => $companyView, 'currentMonthName' => $currentMonthName, 'nextMonthName' => $nextMonthName, 'nextNMonthName' => $nextNMonthName);
        
        return Excel::download(new vpExport($data, $label, $typeExport, $auxTitle), $title);
    }


}
