<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class emailDivulgacao extends Model {
    
    public function getMessage(){
    	
    	$message = "
					<html>
					<head>
					  <title>D|ADS - Discovery Advertisement Data System</title>
					</head>
					<body>
						<span style='font-weight: bold'># D|ADS - Discovery Advertisement Data System </span>
						
						<br>

						<span>
							D|ADS - Discovery Advertisement Data System is a web tool created to be a unique platform consolidating the key performance indicators (KPIs) from Ad Sales. Avoiding data modification and granting reliable information, D|ADS promotes dynamic and automated reports for all company.
						</span>

						<br>

						<span>
							The launching will cover the revenue results for each DLA region:
						</span>
						<ul>
							<li>
								Summary: Monthly results comparing IBMS with Targets, Previous Year and Forecast.
							</li>
							<li>
								Month/Quarter: Sales revenue by networks comparing Targets and Actuals within the month/quarter.
							</li>
							<li>
								Share: Network/Account executive revenue share.
							</li>
							<li>
								Year Over Year: YOY segmented by networks/months with Targets comparison
							</li>
						</ul>
					</body>
					</html>
		";

		return $message;
    }
}
