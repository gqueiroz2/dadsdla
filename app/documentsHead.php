<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class documentsHead extends Model
{

	protected $YTDHead = array('Campaign Sales Office',
								'Sales Rep Sales Office',
								'Calendar Year',
								'Calendar Month',
								'Channel Brand',
								'Channel Feed',
								'Sales Rep',
								'Client',
								'Client Product',
								'Agency',
								'Order Reference',
								'Campaign Reference',
								'Spot Duration',
								'Campaign Currency',
								'Impression Duration (Seconds)',
								'Num of Spot Impressions',
								'Revenue (Campaign Currency)',
								'Net Revenue (Campaign Currency)',
								'Net Net Revenue (Campaign Currency)',
								'Revenue (Current Plan Rate)',
								'Net Revenue (Current Plan Rate)',
								'Net Net Revenue (Current Plan Rate)');



	public function findHead($matrix){

		//primeira linha vai ser sempre o cabeçalhõ, mas faço uma verificação para ver se n existe 

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			$verifier = true;
			for ($j=0; $j <sizeof($matrix[$i]) ; $j++) { 
				if ($matrix[$i][$j] == null) {
					var_dump("entrei");
					$verifier = false;
					break;
				}
			}
			if ($verifier) {
				$index = $i;
				break;
			}
		}

		$verifierYTD = 0;
		$verifierHeader = 0;
		$verifierCMAPS = 0;
		$verifierSAP = 0;

		$nComp = 5;

		for ($i=0; $i < $nComp; $i++) { 
			if ($matrix[$index][$i] == $this->YTDHead[$i]) {
				$verifierYTD++;
			}
			//Falta ter Header CMAPS e SAP
		}

		if ($verifierYTD == $nComp) {
			return 'YTD';
		}

		if ($verifierHeader == $nComp) {
			return 'Header';
		}

		if ($verifierCMAPS == $nComp) {
			return 'CMAPS';
		}

		if ($verifierSAP == $nComp) {
			return 'SAP';
		}

		return false;

	}

   	public function getHead($document){
   		switch ($document) {
   			case 'YTD':
   				return $this->YTDHead;
   				break;
   			
   			default:
   				return false;
   				break;
   		}
   	}
}
