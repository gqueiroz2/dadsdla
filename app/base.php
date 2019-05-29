<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;

class base extends Model{

    protected $month = array( array("Jan",1, "January","JAN"),
                              array("Feb",2, "February","FEV"),
                              array("Mar",3, "March","MAR"),
                              array("Apr",4, "April","ABR"),
                              array("May",5, "May","MAI"),
                              array("Jun",6, "June","JUN"),
                              array("Jul",7, "July","JUL"),
                              array("Aug",8, "August","AGO"),
                              array("Sep",9, "September","SET"),
                              array("Oct",10, "October","OUT"),
                              array("Nov",11, "November","NOV"),
                              array("Dec",12, "December","DEZ")

                            );

    public function TruncateName($form){
        
        if ($form == 'mini_header') {
            $newForm = "Header";
        }elseif ($form == 'cmaps') {
            $newForm = "CMAPS";
        }else{
            $newForm = "IBMS";
        }

        return $newForm;
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
        }else {
            $name = false;;
        }

        return $name;
    }

    public function monthToInt($month){
        $tmp = explode(" ",$month);
        $newMonth = trim($tmp[0]);
        
        for ($m=0; $m < sizeof($this->month); $m++) { 
            if($newMonth == $this->month[$m][2]){
                $intMonth = $this->month[$m][1];
            }
        }
        return $intMonth;
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

    public function formatData($from,$to,$string){
        switch ($from) {
            case 'dd/mm/aaaa':                
                switch ($to) {
                    case 'aaaa-mm-dd':

                        $tmp = explode("/", $string);

                        $dd = $tmp[0];
                        $mm = $tmp[1];
                        $aaaa = $tmp[2];

                        $newString = $aaaa."-".$mm."-".$dd;

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

    public function generateDiv($con,$pr,$region,$year,$currency){
        
        $region = array($region);

        $currency = array($currency);

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
}
