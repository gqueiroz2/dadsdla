<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class viewer extends Model{

	public function matrix($con,$salesRegion,$source,$month,$piNumber,$brand,$value,$year,$salesCurrency,$salesRep){
		$from = ('revenue');

			for ($m=0; $m <($month); $m++) { 
				for ($b=0; $b <($brand); $b++) { 
					for ($s=0; $s <($salesRep); $s++){ 
						
					}
				}
			}
<<<<<<< HEAD
=======
		}

>>>>>>> 2a5a9fa824a1a91342c96eb4a6b91f87851a0ad9
	}

}
