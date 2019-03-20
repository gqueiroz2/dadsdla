<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ytd extends Model{
    /*
        TABLE THAT REFERENCES
        @ytd_{{CURRENT YEAR}}
        example ytd_2019
    */
    protected $table = "ytd";
    protected $sales_office; 
    protected $month; 
    protected $channel_brand;
    protected $channel_feed;
    protected $sales_rep;
    protected $agency;
    protected $client;
    protected $currency;
    protected $charge_type;
    protected $client_product;
    protected $campaign_reference;
    protected $order_reference;
    protected $impression_duration;
    protected $gross_revenue;
    protected $num_of_spot;
    protected $net_revenue;

    protected $fillable = [
        'sales_office', 'month', 'channel_brand','channel_feed','sales_rep','agency','client','currency','charge_type','client_product','campaign_reference','order_reference','impression_duration','gross_revenue','num_of_spot','net_revenue',
    ];

    public function get($con,$table,$region){

        $sql = "SELECT * FROM $table WHERE (sales_office = '$region')";

        $res = $con->query($sql);

        if($res && $res->num_rows > 0){
            $count = 0;
            while ($row = $res->fetch_assoc()) {
                $ytd[$region][$count]["month"] = $row["month"]; # 1
                $ytd[$region][$count]["channel_brand"] = $row["channel_brand"]; # 2
                $ytd[$region][$count]["channel_feed"] = $row["channel_feed"]; # 3
                $ytd[$region][$count]["sales_rep"] = $row["sales_rep"]; # 4
                $ytd[$region][$count]["agency"] = $row["agency"]; # 5
                $ytd[$region][$count]["client"] = $row["client"]; # 6
                $ytd[$region][$count]["currency"] = $row["currency"]; # 7
                $ytd[$region][$count]["charge_type"] = $row["charge_type"]; # 8
                $ytd[$region][$count]["client_product"] = $row["client_product"]; # 9
                $ytd[$region][$count]["campaign_reference"] = $row["campaign_reference"]; # 10
                $ytd[$region][$count]["order_reference"] = $row["order_reference"]; # 11
                $ytd[$region][$count]["impression_duration"] = $row["impression_duration"]; # 12
                $ytd[$region][$count]["gross_revenue"] = $row["gross_revenue"]; # 13
                $ytd[$region][$count]["num_of_spot"] = $row["num_of_spot"]; # 14
                $ytd[$region][$count]["net_revenue"] = $row["net_revenue"]; # 15
                $count ++;
            }
            
        }

        return $ytd;
        /*
        echo "<table style='width:100%;'>";
        echo "<tr>";
            echo "<td> Month </td>";
            echo "<td> Channel Brand </td>";
            echo "<td> Channel Feed </td>";
            echo "<td> Sales Rep </td>";
            echo "<td> Agency </td>";
            echo "<td> Client </td>";
            echo "<td> Currency </td>";
            echo "<td> Charge Type </td>";
            echo "<td> Client Product </td>";
            echo "<td> Campaign Reference </td>";
            echo "<td> Order Reference </td>";
            echo "<td> Impression Duration </td>";
            echo "<td> Gross Revenue </td>";
            echo "<td> Num of Spot </td>";
            echo "<td> Net Revenue </td>";
        echo "</tr>";
        for ($y=0; $y < sizeof($ytd); $y++) { 
            echo "<tr>";
            for ($x=0; $x < sizeof($ytd[$region]); $x++) { 
                echo "<td>".$ytd[$region][$x]["month"]."</td>";
                echo "<td>".$ytd[$region][$x]["channel_brand"]."</td>";
                echo "<td>".$ytd[$region][$x]["channel_feed"]."</td>";
                echo "<td>".$ytd[$region][$x]["sales_rep"]."</td>";
                echo "<td>".$ytd[$region][$x]["agency"]."</td>";
                echo "<td>".$ytd[$region][$x]["client"]."</td>";
                echo "<td>".$ytd[$region][$x]["currency"]."</td>";
                echo "<td>".$ytd[$region][$x]["charge_type"]."</td>";
                echo "<td>".$ytd[$region][$x]["client_product"]."</td>";
                echo "<td>".$ytd[$region][$x]["campaign_reference"]."</td>";
                echo "<td>".$ytd[$region][$x]["order_reference"]."</td>";
                echo "<td>".$ytd[$region][$x]["impression_duration"]."</td>";
                echo "<td>".$ytd[$region][$x]["gross_revenue"]."</td>";
                echo "<td>".$ytd[$region][$x]["num_of_spot"]."</td>";
                echo "<td>".$ytd[$region][$x]["net_revenue"]."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";*/


    }

    public function construct($month,$channel_brand,$channel_feed,$sales_rep,$agency,$client,$currency,$charge_type,$client_product,$campaign_reference,$order_reference,$impression_duration,$gross_revenue,$num_of_spot,$net_revenue){

        $this->sales_office = $sales_office;
        $this->month = $month;
        $this->channel_brand = $channel_brand;
        $this->channel_feed =  $channel_feed;
        $this->sales_rep = $sales_rep;
        $this->agency = $agency;
        $this->client = $client;
        $this->currency = $currency;
        $this->charge_type = $charge_type;
        $this->client_product = $client_product;
        $this->campaign_reference = $campaign_reference;
        $this->order_reference = $order_reference;
        $this->impression_duration = $impression_duration;
        $this->gross_revenue = $gross_revenue;
        $this->num_of_spot =  $num_of_spot;
        $this->net_revenue = $net_revenue;      
    
    }




}
