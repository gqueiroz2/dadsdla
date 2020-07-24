<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\chain;

use App\excel;
use App\base;
use App\region;
use App\brand;
use App\salesRep;
use App\pRate;
use App\agency;
use App\client;
use App\sql;
use App\dataBase;


class InsightsChain extends excel{

    public function handler($con,$table,$spreadSheet,$year){
        $base = new base();
        $bool = $this->firstC($con,$table,$spreadSheet,$base,$year);            
        return $bool;
    }

	public function firstC($con,$table,$spreadSheet,$base,$year){
		$chain = new chain();

		$columns = $this->defineColumns($table,'first');
        var_dump($columns);
		 if($table == "insights" || $table == "insights_bts"){
            $parametter = $table;
        }else{
            $parametter = false;
        }

        $spreadSheet = $chain->assembler($spreadSheet,$columns,$base,$parametter);

        $into = $chain->into($columns);

        $check = 0;
        $mark = 0;

        var_dump('step 1');
        var_dump($table);
        for ($s=0; $s <sizeof($spreadSheet); $s++) { 
        	/*if ($table == 'insights') {
        		$mark++;
        	}else{*/
        		$error = $chain->insert($con,$spreadSheet[$s],$columns,$table,$into);
        		if (!$error) {
        			$check++;
        		}/*
        	}*/	

        }

        if ($check == (sizeof($spreadSheet) - $mark)) {
        	$complete = true;
        }else{
        	$complete = false;
        }

        return $complete;

	}

	public function secondC($sql,$con,$fCon,$sCon,$table,$year =false){
		$chain = new chain();
		$base = new base();
		$columns = $this->defineColumns($table,'first');

		$columns = array_values($columns);
		$columnsS = $this->defineColumns($table,'second');

		$current = $chain->fixToInput($chain->selectFromCurrentTable($sql,$fCon,$table,$columns),$columns);

		if ($table == 'insights') {
			$current = $chain->fixShareAccounts($current);
		}

		$into = $chain->into($columnsS);

		$next = $chain->handlerForNextTable($con,$table,$current,$columns,$year);

		$complete = $chain->insertToNextTable($sCon,$table,$columnsS,$next,$into,$columnsS);

		return $complete;

	}

	public function thirdC($sql,$con,$sCon,$tCon,$table){
		$chain = new chain();
		$base = new base();    	
        $columnsS = $this->defineColumns($table,'second');
    	$columnsT = $this->defineColumns($table,'third');
    	$into = $chain->into($columnsT);

    	$current = $chain->fixToInput($chain->selectFromCurrentTable($sql,$sCon,$table,$columnsS),$columnsS);

    	$cleanedValues = $current;

    	$next = $chain->handlerForLastTable($con,$table,$$cleanedValues,$columnsS);

    	$bool = $chain->insertToLastTable($tCon,$table,$columnsT,$next,$into);

    	return $bool;
	}

    public function toDLA($sql,$con,$tCon,$table,$year,$truncate){
        $base = new base();

        if($truncate){
            $truncateStatement = "TRUNCATE TABLE $table";
            if($con->query($truncateStatement) === TRUE){
                $truncated = true;
            }else{
                $truncated = false;
            }
        }else{
            for ($y=0; $y < sizeof($year); $y++) { 
                $delete[$y] = "DELETE FROM $table WHERE(year = '".$year[$y]."')";     
                if($con->query($delete[$y])){
                }
            }
        }

        $columns = $this->defineColumns($table,'third');
        $into = $chain->into($columns);

        $current = $chain->fixToInput($this->selectFromCurrentTable($aql,$tCon,$table,$columns),$columns);

        $bool = $chain->insertToDLA($con,$table,$columns,$current,$into);

        return $bool;
    }

    public function insertToDLA($con,$table,$columns,$current,$into){
        $count = 0;

        for ($c=0; $c < sizeof($current); $c++) { 
            $bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
            if(!$bool[$c]){
                $count++;
            }
        }

        if($count == sizeof($current)){
            return true;
        }else{
            return false;
        }
    }


    public function defineColumns($table,$recurrency){
        switch ($table) {
            case 'insights':
                switch ($recurrency) {
                    case 'first':
                        return $this->insightsColumnsF;
                        break;
                    case 'second':
                        return $this->insightsColumnsS;
                        break;
                    case 'third':
                        return $this->insightsColumnsT;
                        break;
                    case 'DLA':
                        return $this->insightsColumns;
                        break;
                }
            case 'insights_bts':
                switch ($recurrency) {
                    case 'first':
                        return $this->insightsBTSColumnsF;
                        break;
                    case 'second':
                        return $this->insightsBTSColumnsS;
                        break;
                    case 'third':
                        return $this->insightsBTSColumnsT;
                        break;
                    case 'DLA':
                        return $this->insightsColumns;
                        break;
                }
                break;
        }
    }

    /*
     public $insightsColumnsF = array('brand',
                                     'brand_feed',
                                     'sales_rep',
                                     'agency',
                                     'client',
                                     'month',
                                     'year',
                                     'currency',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'copy_key',
                                     'spot_type',
                                     'date_event',
                                     'duration_spot',
                                     'num_spot', //INT
                                     'gross_revenue', //DOUBLE
                                     'gross_revenue_prate', //DOUBLE
                                     //'agency_commission_percentage', //DOUBLE
    );
    

    public $insightsColumnsS = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency',
                                     'client',
                                     'month',
                                     'year',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'copy_key',
                                     'spot_type',
                                     'date_event',
                                     'duration_spot',
                                     'num_spot', //INT
                                     'gross_revenue', //DOUBLE
                                     'gross_revenue_prate', //DOUBLE
                                     //'agency_commission_percentage', //DOUBLE
    );


    public $insightsColumnsT = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency_id',
                                     'client_id',
                                     'month',
                                     'year',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'copy_key',
                                     'spot_type',
                                     'date_event',
                                     'duration_spot',
                                     'num_spot', //INT
                                     'gross_revenue', //DOUBLE
                                     'gross_revenue_prate', //DOUBLE
                                     //'agency_commission_percentage', //DOUBLE
    );

    public $insightsColumns = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency_id',
                                     'client_id',
                                     'month',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date_event',
                                     'unit_start_time',
                                     'duration_spot',
                                     'copy_key',
                                     'media_item',
                                     'spot_type',
                                     'duration_impression',
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'net_revenue', //DOUBLE
                                     'year'//INT
    );
    */

    public $insightsColumnsF = array('brand',
                                     'brand_feed',
                                     'sales_rep',
                                     'agency',
                                     'client',
                                     'month',                                     
                                     'currency',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date',
                                     'unit_start_time',
                                     'spot_duration',
                                     'copy_key',
                                     'main_house_media',
                                     'spot_type',
                                     'duration_spot',                                     
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'gross_revenue_prate', //DOUBLE
                                     //'agency_commission_percentage', //DOUBLE
    );
    

    public $insightsColumnsS = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency',
                                     'client',
                                     'month',
                                     'year',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'copy_key',
                                     'spot_type',
                                     'date_event',
                                     'duration_spot',
                                     'num_spot', //INT
                                     'gross_revenue', //DOUBLE
                                     'gross_revenue_prate', //DOUBLE
                                     //'agency_commission_percentage', //DOUBLE
    );


    public $insightsColumnsT = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency_id',
                                     'client_id',
                                     'month',
                                     'year',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'copy_key',
                                     'spot_type',
                                     'date_event',
                                     'duration_spot',
                                     'num_spot', //INT
                                     'gross_revenue', //DOUBLE
                                     'gross_revenue_prate', //DOUBLE
                                     //'agency_commission_percentage', //DOUBLE
    );

    public $insightsColumns = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency_id',
                                     'client_id',
                                     'month',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date_event',
                                     'unit_start_time',
                                     'duration_spot',
                                     'copy_key',
                                     'media_item',
                                     'spot_type',
                                     'duration_impression',
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'net_revenue', //DOUBLE
                                     'year'//INT
    );

    public $insightsBTSColumnsF = array('order_reference',
                                         'sales_rep',
                                         'brand',
                                         'spot_type',
                                         'duration_spot',
                                         'schedule_event',
                                         'media_item',
                                         'copy_key',
                                         'duration_impression',
                                         'date_event',
                                         'spot_status',
                                         'gross_revenue'
    );

    public $insightsBTSColumnsS = array('order_reference',
                                         'sales_rep_id',
                                         'brand_id',
                                         'spot_type',
                                         'duration_spot',
                                         'schedule_event',
                                         'media_item',
                                         'copy_key',
                                         'duration_impression',
                                         'date_event',
                                         'spot_status',
                                         'gross_revenue'
	);

    public $insightsBTSColumnsT = array('order_reference',
                                         'sales_rep_id',
                                         'brand_id',
                                         'spot_type',
                                         'duration_spot',
                                         'schedule_event',
                                         'media_item',
                                         'copy_key',
                                         'duration_impression',
                                         'date_event',
                                         'spot_status',
                                         'gross_revenue'

    );
}