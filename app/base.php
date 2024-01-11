<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\region;
use App\sql;

class base extends Model{

    public function currenciesByRegion(){

        $curr = array(
                        "Brazil" => array("BRL","USD"),
                        "Argentina" => array("ARS","USD"),
                        "Colombia" => array("COP","USD"),
                        "Miami" => array("USD"),
                        "Mexico" => array("MXN","USD"),
                        "Chile" => array("CLP","USD"),
                        "Peru" => array("USD"),
                        "Vezenuela" => array("USD"),
                        "Panama" => array("USD"),
                        "Dominican Republic" => array("USD"),
                        "Ecuador" => array("USD"),
                        "Bolivia" => array("USD"),
                        "Puerto Rico" => array("USD"),
                        "US Hispanic" => array("USD")
        );

        return $curr;

    }

    public function fixDate($y,$m,$d){
        if($d < 10){
            $d = "0".$d;
        }

        if($m < 10){
            $m = "0".$m;
        }

        return ($y."-".$m."-".$d);
    }

    public function sourceABVtoComplete($parametter){

        switch ($parametter) {
            case 'CMAPS':
                return "CMAPS";
                break;

            case 'FW':
                return "FreeWheel";
                break;

            case 'SF':
                return "Discovery CRM";
                break;
            case 'BTS':
                return "BTS";
                break;
            case 'ALEPH / WBD':
                return 'ALEPH / WBD';
                break;
            default:
                return "";
                break;
        }

    }

    public function sources(){
        $db = new dataBase();
        $base = new base();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $select = "SELECT * FROM sources_date WHERE (source = 'ALEPH / WBD') ";

        $res = $con->query($select);

        $from = array("source","current_throught");

        $list = $sql->fetch($res,$from,$from);
        //var_dump($list);

        for ($l=0; $l < sizeof($list); $l++) { 
            echo "<div class='row mt-1'>";
                echo "<div class='col' style='margin-top: -10px !important;'>";                                 
                    echo "<div class='container-fluid'>";
                        echo "<div class='row mt-1'>";                       
                            
                            echo "<div class='col-8' style='font-size:10px !important;'>";
                                echo "<span >".$this->sourceABVtoComplete($list[$l]['source'])."</span>";
                            echo "</div>";

                            echo "<div class='col-4' style='float:right;font-size:10px !important;'>";
                                echo "<span >".$this->formatData('aaaa-mm-dd','dd/mm/aaaa',$list[$l]['current_throught'])."</span>";
                            echo "</div>";

                        echo "</div>";
                    echo "</div>";
                echo "</div>";                              
            echo "</div>";    
        }
    }

    public function source($type){
        $db = new dataBase();
        $base = new base();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $select = "SELECT * FROM sources_date WHERE (source = '$type')";

        $res = $con->query($select);

        $from = array("source","current_throught");

        $list = $sql->fetch($res,$from,$from);

        return $list[0]['current_throught'];
        
    }

    public function sourceCMAPS($source){
        $db = new dataBase();
        $base = new base();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        if ($source == 'ALEPH' || $source == 'WBD') {
            $select = "SELECT * FROM sources_date WHERE (source = 'ALEPH / WBD')";
        }else{
            $select = "SELECT * FROM sources_date WHERE (source = '$source')";    
        }
        
        $res = $con->query($select);

        $from = array("current_throught");

        $list = $base->formatData("aaaa-mm-dd","dd/mm/aaaa",$sql->fetch($res,$from,$from)[0]['current_throught']);

        return $list;
    }

    public function arrayToString($array,$hasKey,$key){
        $string = "";
        if($hasKey){
            for ($a=0; $a < sizeof($array); $a++) { 
                $string .= "'".$array[$a][0]."'";
                if($a != sizeof($array)-1){
                    $string .= ",";
                }
            }
        }else{
            for ($a=0; $a < sizeof($array); $a++) { 
                $string .= "'".$array[$a]."'";
                if($a != sizeof($array)-1){
                    $string .= ",";
                }
            }
        }
        return $string;
    }

    protected $agencyComm = array("Argentina" => 7.5150,
                                    "Brazil" => 20.0000,
                                    "Mexico" => 0.9826,
                                    "Colombia" => 9.8631,
                                    "Panama" => 13.9754,
                                    "Miami" => 4.1349,
                                    "New York International" => 2.9584,
                                    "Chile" => 0,
                                    "Peru" => 0,
                                    "Venezuela" => 0,
                                    "Dominican Republic" => 0,
                                    "Ecuador" => 0,
                                    "Bolivia" => 0,
                                    "US Hispanic" => 0,
                                    "Puerto Rico" => 0,
                                    "Europe" => 0,
                                    "Gurugram" => 0,
                                    "Singapore" => 0,
                                    "London" => 0);


    public function superUnique($array,$key){
       $temp_array = [];
       foreach ($array as &$v) {
           if (!isset($temp_array[$v[$key]]))
           $temp_array[$v[$key]] =& $v;
       }
       $array = array_values($temp_array);
       return $array;

    }

    public function getAgencyComm($con,$regionID){
        $r = new region();  

        $region = $r->getRegion($con,$regionID)[0]["name"];

        $return = $this->agencyComm[$region];

        return $return;
    }
 
    public function dateToMonth($date){
        $temp = explode("-",$date);
        
        $year = $temp[0];
        $month = $temp[1];
        

        $rtr = array("year" => $year , "month" => $month);

        return $rtr;
    }

    public function removePercentageSymbol($per, $table){
        $temp = explode("%", $per);
        $percen = floatval( $temp[0] );

        if ($table == "fw_digital") {
            $percentage = $percen;
        }else{
            $percentage = $percen/100;
        }

        return $percentage;
    }

    public function verifyOnBaseCMAPS($con,$what,$arr){       
        $sql = new sql();
        if($what == "client"){
           $something = "client_id";
        }elseif($what == "agencyGroup"){
           $something = "agency_group_id";
        }else{
           $something = "agency_id";
        }

        for ($a=0; $a < sizeof($arr); $a++) {
            
            if($what == "agencyGroup"){
                $join = "LEFT JOIN agency a ON a.ID = y.agency_id";
                $where = "WHERE($something = \"".$arr[$a]."\")";
            }else{
                $join = false;
                $where = "WHERE($something = \"".$arr[$a]."\")";
            }

            $select[$a] = "SELECT SUM(gross) AS mySum 
                                FROM cmaps y
                                $join
                                $where";
            
            $res[$a] = $con->query($select[$a]);
            $from = array("mySum");
            $value[$a] = $sql->fetch($res[$a],$from,$from);
            if( !is_null($value[$a][0]['mySum']) && $value[$a][0]['mySum'] > 0 ){
                $verified[$a] = true;
            }else{
                $verified[$a] = false;
            }
        }
        
        return $verified;        

    }

    public function verifyOnBase($con,$what,$arr){       
        $sql = new sql();
        if($what == "client"){
           $something = "client_id";
        }elseif($what == "agencyGroup"){
           $something = "agency_group_id";
        }else{
           $something = "agency_id";
        }

        for ($a=0; $a < sizeof($arr); $a++) {
            
            if($what == "agencyGroup"){
                $join = "LEFT JOIN agency a ON a.ID = y.agency_id";
                $where = "WHERE($something = \"".$arr[$a]."\")";
            }else{
                $join = false;
                $where = "WHERE($something = \"".$arr[$a]."\")";
            }

            $select[$a] = "SELECT SUM(gross_revenue_prate) AS mySum 
                                FROM ytd y
                                $join
                                $where";

            $res[$a] = $con->query($select[$a]);
            $from = array("mySum");
            $value[$a] = $sql->fetch($res[$a],$from,$from);
            if( !is_null($value[$a][0]['mySum']) && $value[$a][0]['mySum'] > 0 ){
                $verified[$a] = true;
            }else{
                $verified[$a] = false;
            }
        }
        
        return $verified;        

    }

    public $nameReps = array(
                "EC1" => array("New York International"),
                "EC2" => array("Venezuela","Ecuador"),
                "EC3" => array("Panama","Dominican Republic","Puerto Rico"),
                "EC4" => array("Mexico","Panama","Dominican Republic","Puerto Rico"),
                "EC5" => array("Miami","Panama","Dominican Republic","Puerto Rico"),
                "EC6" => array("Panama","Dominican Republic"),
                "EC7" => array("Chile","Peru"),


        );

    public $NameName = array(
            "Brazil"=>array("Brazil"), 
            "Argentina"=>array("Argentina","Chile","Peru","Bolivia"),
            "Colombia"=>array("Colombia"), 
            "Miami"=>array("Miami"), 
            "Mexico"=>array("Mexico"), 
            "Chile"=>array("Chile"), 
            "Peru"=>array("Peru"), 
            "LATAM"=>array("LATAM"),  
            "Venezuela"=>array("Venezuela"),
            "Panama"=>array("Panama"),  
            "New York International"=>array("New York International"),  
            "Dominican Republic"=>array("Dominican Republic"),  
            "Ecuador"=>array("Ecuador"),  
            "Bolivia"=>array("Bolivia"),  
            "Us Hispanic"=>array("Us Hispanic"),  
            "Puerto Rico"=>array("Puerto Rico"),  
            "Europe"=>array("Europe"),  
            "Gurugram"=>array("Gurugram"),  
            "Singapore"=>array("Singapore"),
            "London"=>array("London")
        );

    public $region = array("Brazil","Argentina","Colombia","Miami","Mexico","Chile","Peru","Venezuela","Panama","New York International","Dominican Republic","Ecuador","Bolivia","Puerto Rico");

    public $monthWQ = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $fullMonthEN = array();          

    public $month = array( array("JAN",1, "January","JAN","JANUARY"),
                              array("FEB",2, "February","FEV","FEBRUARY"),
                              array("MAR",3, "March","MAR","MARCH"),
                              array("APR",4, "April","ABR","APRIL"),
                              array("MAY",5, "May","MAI","MAY"),
                              array("JUN",6, "June","JUN","JUNE"),
                              array("JUL",7, "July","JUL","JULY"),
                              array("AUG",8, "August","AGO","AUGUST"),
                              array("SEP",9, "September","SET","SEPTEMBER"),
                              array("OCT",10, "October","OUT","OCTOBER"),
                              array("NOV",11, "November","NOV","NOVEMBER"),
                              array("DEC",12, "December","DEZ","DECEMBER")

                            );

    public function TruncateName($form){
        
        if ($form == 'cmaps') {
            $newForm = "CMAPS";
        }else{
            $newForm = "BKGS";
        }
        
        return $newForm;
    }

    public function filteredRegion($regionId,$special){
        $r = new region();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $regionId = array($regionId);

        $region = $r->getRegion($con,$regionId)[0]["name"];

        $array = $this->NameName[$region];

        if (!is_null($special)) {
            $var = $this->nameReps[$special];
            for ($v=0; $v <sizeof($var) ; $v++) { 
                array_push($array, $var[$v]);
            }

            $array = array_values($array);
        }

        $return = array();

        for ($a=0; $a <sizeof($array) ; $a++) { 
            $return[$a] = $r->getRegionByName($con,$array[$a]);
        }
        
        return $return;
    }

    public function filteredRegionReps($regionId,$kind){
        $r = new region();

        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = $r->getRegion($con,array($regionId))[0]["name"];

        $array = $this->NameName[$region];

        for ($s=0; $s < sizeof($this->nameReps[$kind]); $s++) { 
            array_push($array, $this->nameReps[$kind][$s]);
        }

        $return = array();
        for ($a=0; $a <sizeof($array) ; $a++) { 
            $return[$a] = $r->getRegionByName($con,$array[$a]);
        }
        return $return;
    } 

    public function TruncateTableName($table){
        return (strtoupper($table));
    }

    public function TruncateRegion($region){

        if ($region == "Brazil") {
            $name = "BR";            
        }elseif ($region == "Argentina") {
            $name = "AR";
        }elseif ($region == "Colombia") {
            $name = "COL";
        }elseif ($region == "Miami") {
            $name = "MIA";
        }elseif ($region == "Mexico") {
            $name = "MEX";
        }elseif ($region == "Chile") {
            $name = "CL";
        }elseif ($region == "Peru") {
            $name = "PE";
        }elseif ($region == "Venezuela") {
            $name = "VE";
        }elseif ($region == "Panama") {
            $name = "PA";
        }elseif ($region == "New York International") {
            $name = "NY";
        }elseif ($region == "Dominican Republic") {
            $name = "DR";
        }elseif ($region == "Ecuador") {
            $name = "EC";
        }elseif ($region == "Bolivia") {
            $name = "BO";
        }elseif ($region == "Puerto Rico") {
            $name = "PR";
        }else {
            $name = false;
        }

        return $name;
    }

    public function monthToInt($month){
        

        $tmp = explode(" ",$month);
        $newMonth = trim($tmp[0]);
        
        $intMonth = false;
        for ($m=0; $m < sizeof($this->month); $m++) { 
            if($newMonth == $this->month[$m][2]){
                $intMonth = $this->month[$m][1];
            }
        }

        return $intMonth;
    }
    
    public function monthToIntWBD($month){        

        $tmp = explode(" ",$month);
        $newMonth = trim($tmp[0]);
        
        $intMonth = false;
        for ($m=0; $m < sizeof($this->month); $m++) { 
            if($newMonth == $this->month[$m][3]){
                $intMonth = $this->month[$m][1];
            }
        }

        return $intMonth;
    }    

    public function monthToIntAleph($month){        

        $tmp = explode(" ",$month);
        $newMonth = trim($tmp[0]);
        
        $intMonth = false;
        for ($m=0; $m < sizeof($this->month); $m++) { 
            if($newMonth == $this->month[$m][0]){
                $intMonth = $this->month[$m][1];
            }
        }

        return $intMonth;
    }

    public function monthToIntInsights($month){
        $tmp = explode(" ",$month);
        $newMonth = trim($tmp[0]);
        
        for ($m=0; $m < sizeof($this->month); $m++) { 
            if($newMonth == $this->month[$m][2]){
                $intMonth = $this->month[$m][1];
            }
        }

        $intYear = intval(trim($tmp[1]));

        $rtr = array($intYear,$intMonth);

        return $rtr;//$intMonth;
    }

    public function intToMonth2($month){
        $monthNames = array();
        for ($m=0; $m < sizeof($this->month); $m++) { 
            for ($m2=0; $m2 <sizeof($month) ; $m2++) { 
                if($month[$m2] == $this->month[$m][1]){
                    array_push($monthNames, $this->month[$m][2]);
                }
            }
        }
        return $monthNames;   
    }

    public function intToMonth($month){
        $monthNames = array();
        for ($m=0; $m < sizeof($this->month); $m++) { 
            for ($m2=0; $m2 <sizeof($month) ; $m2++) { 
                if($month[$m2] == $this->month[$m][1]){
                    array_push($monthNames, $this->month[$m][0]);
                }
            }
        }
        return $monthNames;   
    }

    public function monthToIntCMAPS($month){
        $tmp = explode(" ",$month);
        $newMonth = trim($tmp[0]);
        for ($m=0; $m < sizeof($this->month); $m++) { 
            if($newMonth == $this->month[$m][3] ){
                $intMonth = $this->month[$m][1];
            }
        }
        return $intMonth;
    }

    public function breakTimeStamp($timestamp){

        if(isset($timestamp)){
            $tmp = $this->breakInTwo($timestamp);
            $date = $this->formatData("aaaa-mm-dd","dd/mm/aaaa",$tmp["date"]);
            $time = $this->fixTime($tmp["time"]);
            $rtr = array("date" => $date, "time" => $time);    
        }else{
            return false;
        }
        
        return $rtr;
    }

    public function breakInTwo($timestamp){

        $tmp = explode(" ", $timestamp);
        
        $rtr = array("date" => $tmp[0],"time" => $tmp[1]);

        return $rtr;

    }

    public function fixTime($time){
        $newTime = substr($time,0,5);
        return $newTime;

    }

    public function formatHour($from,$to,$string){
        switch ($from) {
            case 'hh:mm:ss':
                switch ($to) {
                    case 'hh:mm':
                        
                        $tmp = explode(":", $string);
                        $hour = $tmp[0].":".$tmp[1];
                        return $hour;

                        break;
                }
                break;
            default:
                break;
        }


    }

    public function formatData($from,$to,$string){
        switch ($from) {
            case 'mm/dd/aaaa':                
                
                switch ($to) {
                    case 'aaaa-mm-dd':

                        if($string != ''){
                            $tmp = explode("/", $string);
                                                    
                            $dd = $tmp[1];

                            if($dd < 10){
                                $dd = "0".$dd;
                            }

                            $mm = $tmp[0];

                            if($mm < 10){
                                $mm = "0".$mm;
                            }
                            
                            $aaaa = $tmp[2];

                            $newString = $aaaa."-".$mm."-".$dd;
                        }else{
                            $newString = "2000-01-01";
                        }
                        break;
                        
                    default:
                        $newString = false;
                        break;
                }
            break;

            case 'dd/mm/aaaa':                
                switch ($to) {
                    case 'aaaa-mm-dd':

                        if($string != ''){

                            $tmp = explode("/", $string);

                            $dd = $tmp[0];

                            if($dd < 10){
                                $dd = "0".$dd;
                            }

                            $mm = $tmp[1];

                            if($mm < 10){
                                $mm = "0".$mm;
                            }
                            $aaaa = $tmp[2];

                            $newString = $aaaa."-".$mm."-".$dd;

                        }else{
                            $newString = "2000-01-01";
                        }
                        
                        break;
                    
                    default:
                        $newString = false;
                        break;
                }
            break;

            case 'aaaa-mm-dd':                
                switch ($to) {
                    case 'dd/mm/aaaa':

                        $tmp = explode("-", $string);

                        $dd = $tmp[2];
                        $mm = $tmp[1];
                        $aaaa = $tmp[0];

                        $newString = $dd."/".$mm."/".$aaaa;

                        break;
                    
                    default:
                        $newString = false;
                        break;
                }
            break;
                        
            default:
                $newString = false;
                break;
        }

        return $newString;

    }

    public function getMonth(){
        return $this->month;
    }

    public function getYtdMonth(){
        $month = date('n');
        $tmp = array();

        for ($i=0; $i <sizeof($this->month) ; $i++) { 
            array_push($tmp,$this->month[$i]);
            if ($month == $this->month[$i][1]) {
                break;
            }
        }

        return $tmp;
    }

    public function adaptCurrency($con,$pr,$save,$currencyID,$cYear,$type = false){
        
        if($type){
            $curr = "currencyID";
        }else{
            $curr = "currency_id";
        }
 
        for ($s=0; $s < sizeof($save); $s++) { 
            if ($currencyID == $save[$s][$curr]) {
                $currencyCheck[$s] = false;
                $newCurrency[$s] = false;
                $oldCurrency[$s] = false;
            }else{
                $currencyCheck[$s] = true;
                $newCurrency[$s] = $pr->getPrateByCurrencyAndYear($con,$currencyID,$cYear);
                $oldCurrency[$s] = $pr->getPrateByCurrencyAndYear($con,$save[$s][$curr],$cYear);            
            }

        }

        $array = array( "currencyCheck" => $currencyCheck,
                        "newCurrency" => $newCurrency,
                        "oldCurrency" => $oldCurrency
                        );

        return $array;


    }

    public function adaptValue($value,$save,$regionID,$listOfClients,$type = false){
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);


        if($type){
            $vall = "typeOfValue";
        }else{
            $vall = "type_of_value";
        }

        $multValue = array();

        for ($i=0; $i < sizeof($save); $i++) { 
            $multValue[$i] = array();
        }

        for ($s=0; $s < sizeof($save); $s++) {
            if ($value == strtolower($save[$s][$vall])) {
                $valueCheck[$s] = false;
                $multValue[$s] = false;
                $mult = false;
            }else{
                $from = array("agency_commission");

                $sql = new sql();

                $tmp = array($regionID);

                $mult = $this->getAgencyComm($con,$tmp);
                
                for ($c=0; $c <sizeof($listOfClients) ; $c++) { 
                    $select[$c] = "SELECT agency_commission FROM sf_pr WHERE client_id = \"".$listOfClients[$c]['clientID']."\" AND region_id = \"".$regionID."\"";
                    $res[$c] = $con->query($select[$c]);
                    $resp[$c] = ($sql->fetch($res[$c],$from,$from)[0]['agency_commission']);
                    
                    if (is_null($resp[$c])) {
                        $resp[$c] = $mult;
                    }else{
                        $resp[$c] = floatval($resp[$c])*100;
                    }

                    if (strtolower($value) == "net") {
                        $multValue[$s][$c] = (100 - $resp[$c])/100;
                    }elseif(strtolower($value) == "gross"){
                        $multValue[$s][$c] = 1/(1-($resp[$c]/100));
                    }

                }

                $valueCheck[$s] = true;

            }
        }

        $array = array("valueCheck" => $valueCheck,
                       "multValue" => $multValue,
                       "mult" => $mult

        );

        return $array;

    }

    public function getBrandColor($brand){
        $rtr = false;

        for ($i=0; $i <sizeof($this->brands) ; $i++) { 
            if ($brand == $this->brands[$i]) {
                $rtr = $this->brandsColor[$i];
            }
        }

        return $rtr;
    }

    public function getBrandTextColor($brand){
        $rtr = false;

        for ($i=0; $i <sizeof($this->brands) ; $i++) { 
            if ($brand == $this->brands[$i]) {
                $rtr = $this->brandTextColor[$i];
            }
        }

        return $rtr;
    }

    public function monthToQuarter($month){

        $quarter = array();

        for ($m=0; $m <sizeof($month) ; $m++) { 
            if ($month[$m] == 1 || $month[$m] == 2 || $month[$m] == 3) {
                array_push($quarter, "Q1");
            }elseif ($month[$m] == 4 || $month[$m] == 5 || $month[$m] == 6) {
                array_push($quarter, "Q2");
            }elseif ($month[$m] == 7 || $month[$m] == 8 || $month[$m] == 9) {
                array_push($quarter, "Q3");
            }else{
                array_push($quarter, "Q4");
            }
        }
        
        $quarter = array_unique($quarter);
        $quarter = array_values($quarter);

        return $quarter;
    }

    protected $salesRegion = array("Argentina","Brazil","Colômbia","México","Pan-Regional");

    protected $brand = array("DC","HH","DK","AP","TLC","ID","DT","FN","ONL");
    protected $brands = array("DC","HH","DK","AP","TLC","ID","DT","FN","ONL", "VIX", "OTH", "HGTV");
    protected $brandsColor = array("#0070c0","#ff3300","#ffff00","#009933","#ff0000","#000000","#000066","#ff0000","#6600ff","#004b84",'#ffffff',"#88cc00");
    protected $brandTextColor = array("#000000","#000000","#000000","#000000","#000000","#ffffff","#ffffff","#000000","#000000","#000000","#000000","#000000");
    protected $brandTarget = array(  "Discovery",
                                     "Discovery Home and Health",
                                     "Discovery Kids",
                                     "Animal Planet",
                                     "TLC",
                                     "ID",                                   
                                     "Discovery Turbo",
                                     "Food Network",
                                     array("Digital - VIX",
                                     "Digital - Others")
                                    //Self Service 
                                 );
    protected $brandIBMS = array( "Discovery",
                                  "Discovery Home and Health",
                                  "Discovery Kids",
                                  "Animal Planet",
                                  "TLC",
                                  "ID",
                                  "Discovery Turbo",
                                  "Food Network",
                                  array("Digital - VIX",
                                     "Digital - Others")
                                );

    public function handleBrand($tmp){
        
        for ($t=0; $t < sizeof($tmp); $t++) {
            $brands[$t] = json_decode(base64_decode($tmp[$t]));
        }

        return $brands;
    }

    public function handleBrandSS($tmp){
        
        $brand = json_decode(base64_decode($tmp));
        return $brand;

    }

    public function generateDiv($con,$pr,$region,$year,$currencyID){
        
        $region = array($region);

        $currency = array($currencyID);
        
        $currency = $pr->getCurrency($con,$currency)[0];

        if ($currency["name"] == 'USD') {
            $div = 1;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,$region,$year);            
        }
 
        return $div; 

    }


    public function generateDivCMAPS($con,$pr,$region,$year,$currencyID){
        
        $region = array($region);

        $currency = array($currencyID);
        
        $currency = $pr->getCurrency($con,$currency)[0];

        if ($currency["name"] == 'USD') {
            $div = $pr->getPRateByRegionAndYear($con,$region,$year);            
        }else{
            $div = 1;            
        }

        return $div; 

    }

    public function getSalesRegion(){   
        return $this->salesRegion;
    }

    public function getBrand(){
        return $this->brand;
    }

    public function getBrands(){
        return $this->brands;
    }

    public function pattern($type,$region,$pattern,$from,$to,$con){
        switch ($type) {
            case 'Month':               
                $np = $this->matchMonth($pattern,$from,$to,$con);
                break;
            case 'Brand':
                $np = $this->matchBrand($region,$pattern,$from,$to,$con);
                break;
            case 'Source':              
                $np = $this->matchSource($region,$pattern,$from,$to,$con);
                break;
        }

        return $np;
    }

    public function matchMonth($pattern,$from,$to,$con){
        switch ($from) {
            case 'base':                
                if( is_null($pattern) ){
                    $pattern = $this->month;    
                }

                $match = $this->subMatchMonth($pattern,$from,$to);
                return $match;
                break;                          
            default:
                
                break;
        }
    }

    public function subMatchMonth($pattern,$from,$to){      
        switch ($to) {
            case 'target':
                
                if($from == "base"){
                    return $pattern;
                }

                break;          
            case 'ibms':
                
                if($from == "base"){
                    return $pattern;
                }


                break;          
            default:
                # code...
                break;
        }
    }

    public function matchBrand($region,$pattern,$from,$to,$con){        

        switch ($from) {
            case 'base':

                $match = $this->subMatchBrand($pattern,$from,$to);
                return $match;

                break;          

            case 'base':            
                
                break;
        }       
    }

    public function subMatchBrand($pattern,$from,$to){              
        switch ($to) {
            case 'target':

                $subMatch = $this->matchArray($from,$pattern);              
                $finalMatch = $this->getFinalArray($to,$subMatch);
                return $finalMatch;

                break;

            case 'ibms':

                $subMatch = $this->matchArray($from,$pattern);              
                $finalMatch = $this->getFinalArray($to,$subMatch);
                return $finalMatch;

                break;
            
            default:
                # code...
                break;
        }
    }

    public function matchArray($from,$pattern){
        switch ($from) {
            case 'base':                
                $cc = 0;
                for ($p=0; $p < sizeof($pattern); $p++) {
                    for ($b=0; $b < sizeof($this->brand); $b++) { 
                        if($pattern[$p] == $this->brand[$b]){
                            $matchArray[$cc] = $b;
                            $cc++;
                            break;
                        }
                    }
                }
                
                return $matchArray;
                break;          
            default:
                # code...
                break;
        }
    }

    public function getFinalArray($to,$array){
        switch ($to) {
            case 'target':
                
                for ($a=0; $a < sizeof($array); $a++) { 
                    $fArray[$a] = $this->brandTarget[$array[$a]];
                }

                return $fArray;
                break;

            case 'ibms':
                
                for ($a=0; $a < sizeof($array); $a++) { 
                    $fArray[$a] = $this->brandIBMS[$array[$a]];
                }

                return $fArray;
                break;

            
            default:
                # code...
                break;
        }
    }

    

    public function matchSource($region,$pattern,$from,$to,$con){
        switch ($pattern) {
            case 'target':
                $match = "target";
                return $match;
                break;
            case 'ibms':
                $match = "ytd";
                return $match;
                break;
            
            default:
                # code...
                break;
        }       
    }

    public function defineCurrency($con,$region,$year,$currency){       
        if($currency == "usd"){
            return 1.0;
        }

        $sql = "SELECT ".strtolower($region)." FROM p_rate WHERE (year = '$year')";

        $res = $con->query($sql);

        if($res && $res->num_rows > 0){
            $row = $res->fetch_assoc();
            $div = doubleval($row[strtolower($region)]);
        }else{
            $div = FALSE;
        }

        return $div;
    }

    public function defineValue($source,$value){        

        if($value == "gross"){
            if($source == "target"){
                return "GROSS REVENUES";
            }elseif($source == "ibms"){
                return "gross_revenue";
            }
        }elseif($value == ""){
            if($source == "target"){
                return "NET REVENUES";
            }elseif($source == "net_revenue"){
                
            }
        }else{
            return false;
        }
    }

    public function getRegionByUser($con,$user){

        $select = "SELECT u.region_id FROM user u WHERE (u.name = '".$user."')";

        $res = $con->query($select);
        $fetched = $res->fetch_assoc();

        return $fetched;
    }
}
