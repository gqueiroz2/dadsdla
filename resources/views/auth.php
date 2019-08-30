<?php
    
        $userName = Request::session()->get('userName');
        $regionName = Request::session()->get('userRegion');
        $regionID = Request::session()->get('userRegionID');
        $userEmail = Request::session()->get('userEmail');
        $userLevel = Request::session()->get('userLevel');
        $userSalesRepGroup = Request::session()->get('userSalesRepGroup');
        $userSalesRepGroupID = Request::session()->get('userSalesRepGroupID');
        $performanceName = Request::session()->get('performanceName');
        $special = Request::session()->get('special');

?>