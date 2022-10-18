<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;
use mysqli;


class bvModel extends Model{
    
    public function getSalesRepByAgencyGroup(String $agencyGroupId, int $year, Object $con, Object $sql){
        $pYear = $year - 1;
        $ppYear = $year - 2;
        $query = "SELECT distinct sr.name as SRName from cmaps cm 
                   left join agency a on a.ID = cm.agency_id 
                   left join client c on c.ID = cm.client_id 
                   left join sales_rep sr on sr.ID = cm.sales_rep_id  
                   left join agency_group ag on ag.ID = a.agency_group_id 
                   where ag.ID = $agencyGroupId
                   and cm.`year` in ($ppYear, $pYear, $year)
                   order by 1 asc";

        $result= $con->query($query);
        $from = array("SRName");
        $value = $sql->fetch($result, $from, '0');
        var_dump($value);
        return $value;
    }

    public function tableBV(String $agencyGroupId, int $year, Object $con){
        $sql = new sql();
        $year = (int)date("Y");

        $result = $this->getSalesRepByAgencyGroup($agencyGroupId, $year, $con, $sql);
    }
}
