<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class viewer extends Model{

	public function baseMatrix($con,$brands,$salesRep,$months,$grossRevenue,$netRevenue,$mapNumber){
		$from = array('revenue');

		for ($mn=0; $mn <sizeof($mapNumber); $mn++) { 
			for ($m=0; $m <sizeof($months); $m++) { 
				for ($b=0; $b < sizeof($brands); $b++) { 
					for ($s=0; $s <sizeof($salesRep) ; $s++) { 
						
						var_dump($mapNumber[$mn],$months[$m],$brands[$b],$salesRep[$s]);
					}
				}
			}
		}

	}

}
