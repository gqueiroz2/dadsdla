<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;

class subRankings extends rank {
    
    public function getSubResults($con, $mtx, $brands, $type, $region, $value, $currency, $months, $years){
        //var_dump($currency);
        if ($type == "agencyGroup") {
            $name = "agency";
        }else{
            $name = "client";
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $res[$y] = $this->getSubValues($con, "ytd", $name, $type, $brands, $region, $value, $years[$y], $mtx, $months, $currency, $y);
        }

        return $res;

    }

    public function checkColumn(){
        
        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            # code...
        }elseif ($mtx[$m][0] == "VAR %") {
            # code...
        }else{

        }
    }

    public function assembler($sub, $years, $type){
        
        if ($type == "agencyGroup") {
            $var = "Agencys";
            $type2 = "agency";
        }else{
            $var = "Clients";
            $type2 = "client";
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = $var;

        for ($l=0; $l < sizeof($years); $l++) { 
            
            $mtx[(sizeof($years)+$l+1)][0] = "Rev. ".$years[$l];
        }
        
        $fun = "array_multisort(";

        for ($m=0; $m < sizeof($sub); $m++) { 
            $fun .= "\$sub[".$m."], SORT_ASC";

            if ($m != sizeof($sub)-1) {
                $fun .= ", ";
            }
        }

        $fun .= ");";
        
        eval($fun);
                
        var_dump($sub[0]);
        var_dump($sub[1]);
        var_dump($sub[2]);
        //var_dump($mtx);

        for ($s=0; $s < sizeof($sub[0]); $s++) { 
            for ($s2=0; $s2 < sizeof($sub[0][$s]); $s2++) { 
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    # code...
                }
            }
        }

        /*for ($s=0; $s < sizeof($sub); $s++) { 
            for ($s2=0; $s2 < sizeof($sub[$s]); $s2++) { 
                if (is_array($sub[$s][$s2])) {
                    for ($s3=0; $s3 < sizeof($sub[$s][$s2]); $s3++) { 
                        array_push($mtx[$s], ($s3+1));
                        array_push($mtx[sizeof($years)+$s+1], $sub[$s][$s2][$s3]['total']);
                        array_push($mtx[sizeof($years)], $sub[$s][$s2][$s3][$type2]);
                    }   
                }
            }
        }*/

        //var_dump($mtx);
        return $mtx;
    }
}
