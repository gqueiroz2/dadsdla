<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\client;
use App\agency;


class relationship extends Model{

	protected $sortP;

	public function compare($object1,$object2){
        return $object1[$this->sortP] > $object2[$this->sortP];
    }

	public function getStructure($con,$region,$type){
		switch ($type) {
			case 'client':
				$this->sortP = "client";
				$ins = new client();
				$list = $ins->getRelationshipClient($con,array($region));
				usort($list, array($this,'compare'));
				$childOfList = $this->getChild($con,$ins,$region,$type,$list);

				break;

			case 'agency':
				$this->sortP = "agencyGroup";
				$ins = new agency();
				$list = $ins->getRelationshipAgencyGroup($con,array($region));				
				usort($list, array($this,'compare'));
				$childOfList = $this->getChild($con,$ins,$region,$type,$list);
				break;
		}
		return $childOfList;	
	}

	public function getChild($con,$ins,$region,$type,$list){
		if($type == "client"){
			for ($l=0; $l < sizeof($list); $l++) { 
				
				$temp[$l] =  $ins->getClientUnitByClientID($con,$list[$l]["clientID"]);
				$child[$l]['client'] = $list[$l]['client'];
				$child[$l]['clientID'] = $list[$l]['clientID'];
				for ($m=0; $m < sizeof($temp[$l]); $m++) { 
					if( !is_null($temp[$l][$m]['clientUnit']) ){						
						$child[$l]['clientUnit'][$m] = $temp[$l][$m]['clientUnit'];
					}else{
						$child[$l]['clientUnit'] = false;
					}
				}

			}
		}else{
			//var_dump($list);
			for ($l=0; $l < sizeof($list); $l++) { 
				$temp[$l] =  $ins->getAgencyByAgencyGroupID($con,$list[$l]["agencyGroupID"]);
				$child[$l]['agencyGroup'] = $list[$l]['agencyGroup'];
				$child[$l]['agencyGroupID'] = $list[$l]['agencyGroupID'];
				//var_dump($temp);
				if (!is_array($temp[$l])) {
					//var_dump($temp[$l]);
					$child[$l]['agency'][0]['name'] = $temp[$l][0]['agency'];
					$child[$l]['agency'][0]['id'] = $temp[$l][0]['agencyID'];
					$child[$l]['agency'][0]['agencyUnit'] = false;
					//var_dump($child[$l]);
				} else {
					for ($m=0; $m < sizeof($temp[$l]); $m++) {
						//var_dump($temp[$l]); 
						//var_dump($child[$l]);
						if( !is_null($temp[$l][$m]['agency']) ){						
							$child[$l]['agency'][$m]['name'] = $temp[$l][$m]['agency'];
							$child[$l]['agency'][$m]['id'] = $temp[$l][$m]['agencyID'];
							$temp2[$m] = $ins->getAgencyUnitByAgencyID($con,$child[$l]['agency'][$m]['id']);
							//var_dump($temp2);
							if($temp2[$m]){
								for ($n=0; $n < sizeof($temp2[$m]); $n++) { 
									$child[$l]['agency'][$m]['agencyUnit'][$n] = $temp2[$m][$n]['agencyUnit'];
								}
							}else{
								$child[$l]['agency'][$m]['agencyUnit'] = false;
							}
						}else{
							$child[$l]['agency'] = false;
						}
					}
				}
			}
		}
		return $child;

	}
}
