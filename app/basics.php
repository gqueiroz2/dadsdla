<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class basics extends Model
{
    //função para formatar a data
	public function formatData($pattern, $from, $to){
			switch($from) {
				case "AAAA-MM-DD":
					$ptr = '-';
					$ord = "AMD";
					$nd = $this->subData($pattern,$ptr,$ord,$to);
					break;
			}
	}

	public function subData($pattern, $ptr, $ord, $to){
			$temp = explode($ptr, $pattern);

			$AMD[$ord[0]] = $temp[0];
			$AMD[$ord[1]] = $temp[1];
			$AMD[$ord[2]] = $temp[2];

			switch ($to) {
				case "AAAA-MM-DD":
					return ($AMD["A"]."-".$AMD["M"]."-".$AMD["D"]);
					break;
				
				default:
					return false;
					break;
			}
	}
}
