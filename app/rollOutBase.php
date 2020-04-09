<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;

class rollOutBase extends Model{
    public function checkFirstLine($line){
    	$c = 0;
    	$check = false;
    	$pos = false;

    	while( !$check ){
    		if($c > 0){
    			if(is_null($line[$c])){
	    			if( is_null($line[$c+1]) ){
						if( is_null($line[$c+2]) ){
							$check = true;
						}else{
							$c++;
						}
	    			}else{
	    				$c++;
	    			}
	    		}else{
	    			$c++;
	    		}
    		}else{
    			if( is_null($line[$c+1]) ){
					if( is_null($line[$c+2]) ){
						$check = true;
					}else{
						$c++;
					}
    			}else{
    				$check = true;
    			}
    		}
    	}

    	return ($c);
    }
}
