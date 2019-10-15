<?php

namespace App\Exports;

use App\ytd;
use Maatwebsite\Excel\Concerns\FromCollection;

class ytdExport implements FromArray {

	private $collect;

	public function __construct($collect){
		$this->collect = $collect;
	}


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$collect = array();

    	for ($a=0; $a < sizeof($this->collect); $a++) { 
    		
    		$collect[$a]['campaign_sales_office'] = $this->collect[$a]['campaign_sales_office'];
    		$collect[$a]['sales_representant_office'] = $this->collect[$a]['sales_representant_office'];
    		$collect[$a]['brand'] = $this->collect[$a]['brand'];
    		$collect[$a]['sales_rep'] = $this->collect[$a]['sales_rep'];
    		$collect[$a]['client'] = $this->collect[$a]['client'];
    		$collect[$a]['agency'] = $this->collect[$a]['agency'];
    		$collect[$a]['campaign_currency'] = $this->collect[$a]['campaign_currency'];
    		$collect[$a]['year'] = $this->collect[$a]['year'];
    		$collect[$a]['month'] = $this->collect[$a]['month'];
    		$collect[$a]['brand_feed'] = $this->collect[$a]['brand_feed'];
    		$collect[$a]['client_product'] = $this->collect[$a]['client_product'];
    		$collect[$a]['order_reference'] = $this->collect[$a]['order_reference'];
    		$collect[$a]['campaign_reference'] = $this->collect[$a]['campaign_reference'];
    		$collect[$a]['spot_duration'] = $this->collect[$a]['spot_duration'];
    		$collect[$a]['impression_duration'] = $this->collect[$a]['impression_duration'];
    		$collect[$a]['num_spot'] = $this->collect[$a]['num_spot'];
    		$collect[$a]['revenue'] = $this->collect[$a]['revenue'];
    	}

        return $collect;
    }

    public function headings(): array{
    	
    	return [
    		'campaign_sales_office',
    		'sales_representant_office',
    		'brand',
    		'sales_rep',
    		'client',
    		'agency',
    		'campaign_curency',
    		'year',
    		'month',
    		'brand_feed',
    		'client_product',
    		'order_reference',
    		'campaign_reference',
    		'spot_duration',
    		'impression_duration',
    		'num_spot_impressions',
    		'revenue',
    	];
    }
}
