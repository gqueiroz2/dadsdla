<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;

class base extends Model{

    protected $monthNumber = array('1','2','3','4','5','6','7','8','9','10','11','12');

    protected $month = array( array("Jan",1),
                              array("Feb",2),
                              array("Mar",3),
                              array("Apr",4),
                              array("May",5),
                              array("Jun",6),
                              array("Jul",7),
                              array("Aug",8),
                              array("Sep",9),
                              array("Oct",10),
                              array("Nov",11),
                              array("Dec",12)

                            );


    public function getMonth(){
        return $this->month;
    }

    protected $salesRegion = array("Argentina","Brazil","Colômbia","México","Pan-Regional");

    protected $brand = array("DC","HH","DK","AP","TLC","ID","DT","FN","ONL");
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

    
    public function handleBrand($con,$b,$array){
        $b = new brand();
        $check = false;
        for ($a=0; $a < sizeof($array); $a++) { 
            if($array[$a] == 'dn'){
                $check = true;
            }
        }
        if($check){ 
            $brand = $b->getBrand($con);
            for ($i=0; $i < sizeof($brand); $i++) { 
                $brandID[$i] = $brand[$i]['id'];
            }
            $return = $brandID;
        }else{
            $return = $array;
        }
        return $return;
    }

    public function getMonthNumber(){
        return $this->monthNumber;
    }

    public function getYtdMonthNumber(){
        $month = date('n');
        $tmp = array();

        for ($i=0; $i <sizeof($this->monthNumber) ; $i++) { 
            array_push($tmp,$this->monthNumber[$i]);
            if ($month == $this->monthNumber[$i]) {
                break;
            }
        }

        return $tmp;
    }

    public function getSalesRegion(){   
        return $this->salesRegion;
    }

    public function getBrand(){
        return $this->brand;
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
