<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sql extends Model{
    
    public function larica($con, $columns, $table, $join = null, $where = null, $order_by = 1, $limit = false){     
        $sql = "SELECT $columns FROM $table $join $where ORDER BY 1 $limit";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function select($con, $columns, $table, $join = null, $where = null, $order_by = 1, $limit = false , $groupBy = false){    	
        $sql = "SELECT $columns FROM $table $join $where $groupBy ORDER BY 1 $limit";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectDistinct($con, $columns, $table, $join = null, $where = null, $order_by = 1, $limit = false , $groupBy = false){      
        $sql = "SELECT DISTINCT $columns FROM $table $join $where $groupBy ORDER BY $order_by $limit";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectSum($con,$sum,$as, $table, $join = null, $where = null, $order_by = 1, $limit = false){
        $sql = "SELECT SUM($sum) AS $as FROM $table $join $where";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectSum2($con,$sum,$as, $table, $join = null, $where = null, $order_by = 1, $limit = false){
        $sql = "SELECT SUM($sum) AS $as FROM $table $join $where";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectGroupBy($con, $columns, $table, $join = null, $where = null, $order_by = 1, $group_by = 1, $order="asc"){
        $sql = "SELECT $columns FROM $table $join $where GROUP BY $group_by ORDER BY $order_by $order";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectGroupByV($con, $columns, $table, $join = null, $where = null, $order_by = 1, $group_by = 1, $order="asc"){
        $sql = "SELECT $columns FROM $table $join $where GROUP BY $group_by ORDER BY $order_by $order";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectGroupBy2($con, $columns, $table, $join = null, $where = null, $order_by = 1, $group_by = false, $order=false){
        if($group_by){
            $grp = "GROUP BY ".$group_by;
        }else{
            $grp = false;
        }

        if($order){
            $order = "ORDER BY $order_by";
        }

        $sql = "SELECT $columns FROM $table $join $where $grp ORDER BY $order_by $order";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    public function selectGroupByDistinct($con, $columns, $table, $join = null, $where = null, $order_by = 1, $group_by = false, $order=""){
        if($group_by){
            $grp = "GROUP BY ".$group_by;
        }else{
            $grp = false;
        }

        $sql = "SELECT DISTINCT $columns FROM $table $join $where $grp ORDER BY $order_by $order";
        //echo "<pre>".$sql."</pre><br>";
        $res = $con->query($sql);
        return $res;
    }

    //só pode ser usada se o info possuir 2 posições, nem mais nem menos
    public function selectWithUnion($con, $whereArray, $info, $group_by, $order_by, $order=""){
        
        $columns = $info[0]['query'];
        $table = $info[0]['table'];
        $join = $info[0]['join'];
        $where = $whereArray[0];
        
        $sql = "SELECT $columns FROM $table $join $where GROUP BY $group_by";

        $sql .= " UNION ";

        $columns = $info[1]['query'];
        $table = $info[1]['table'];
        $join = $info[1]['join'];
        $where = $whereArray[1];

        $sql .= "SELECT $columns FROM $table $join $where GROUP BY $group_by ORDER BY $order_by $order";

        //echo "<pre>".$sql."</pre>";

        /*for ($i=0; $i < strlen($sql); $i++) { 
            echo $sql[$i];
        }
        echo "<br>";
        echo "<br>";
        echo "<br>";*/

        $res = $con->query($sql);

        return $res;
    }

    public function insert($con,$table,$columns,$values){
        
        $insert = "INSERT INTO $table ($columns) VALUES ($values)";
        if($con->query($insert) === true){
            $rtr["bool"] = true;
            $rtr["msg"] = "A New record on the table $table was successfully created!";
        }else{
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: ".$insert."<br>".$con->error;
        }
        return $rtr;
    }

    public function fetch($result,$from,$to){
    	if($result && $result->num_rows > 0){
    		$count = 0;
    		while ($row = $result->fetch_assoc()){
                for ($i=0; $i < sizeof($from); $i++) {
                    $info[$count][$to[$i]] = $row[$from[$i]];  				
    			}
    			$count++;
    		}
    	}else{
    		$info = false;
    	}

    	return $info;

    }

    public function fetch2($result,$from,$to){
        var_dump($from);
        var_dump($to);
        $vlau = array();
        if($result && $result->num_rows > 0){
            $count = 0;
            //var_dump($result->fetch_assoc());
            while ($row = $result->fetch_assoc()){
                $vlau[] = $row;
                var_dump($vlau);
                for ($i=0; $i < sizeof($from); $i++) {
                    $info[$count][$to[$i]] = $row[$from[$i]];               
                }
                $count++;
            }
        }else{
            $info = false;
        }
        
        return $info;

    }

    public function fetchSum($result,$sum){

        if($result && $result->num_rows > 0){            
            $row = $result->fetch_assoc();                
            $info[$sum] = doubleval($row[$sum]);               
        }else{
            $info = false;
        }

        return $info;

    }

    public function setUpdate($columns, $values){

        $set = "SET ";
        for ($i=0; $i <sizeof($columns) ; $i++) { 
            if ($i == sizeof($columns)-1) {
                $set .= "$columns[$i] = \"$values[$i]\"";
            }else{
                $set .= "$columns[$i] = \"$values[$i]\", ";
            }
        }

        return $set;
    }

    public function updateValues($con,$tableName,$set,$where){
        $db = new Database();
        $default = $db->defaultConnection();

        $sql = "UPDATE $default.$tableName $set $where";
        if ($con->query($sql) === TRUE) {
            $rtr["bool"] = true;
            $rtr["msg"] = "Successfully updated!";
        } else {
            echo "Error updating record: " . $con->error;
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: Update failed, no data matching encountered";

        }

        return $rtr;
    }

    public function where($columns,$variables){
        //var_dump($variable);
        $where = "WHERE ";
        
        for ($i=0; $i < sizeof($columns); $i++) { 
            if ($i == sizeof($columns)-1) {

                if (is_array($variables[$i])) {
                    
                    $where .= "($columns[$i] IN (";

                    for ($v=0; $v < sizeof($variables[$i]); $v++) { 
                        if ($v == sizeof($variables[$i])-1) {
                            $where .= "\"".$variables[$i][$v]."\"))";
                        }else{
                            $where .= "\"".$variables[$i][$v]."\",";
                        }
                    }
                //var_dump($where);
                    
                }else{
                    $where .= "($columns[$i] = \"$variables[$i]\")";
                }
            }else{
                if (is_array($variables[$i])) {

                    $where .= "($columns[$i] IN (";
                    
                    for ($v=0; $v < sizeof($variables[$i]); $v++) { 
                        if ($v == sizeof($variables[$i])-1) {
                            $where .= "\"".$variables[$i][$v]."\")) AND ";
                        }else{
                            $where .= "\"".$variables[$i][$v]."\",";
                        }
                    }
                }else{
                    $where .= "($columns[$i] = \"$variables[$i]\") AND ";
                }
            }
        }
        
        return $where;

    }

    public function whereONLAdjust($columns,$variables){
        $where = "WHERE ";
        
        for ($i=0; $i < sizeof($columns); $i++) { 
            if($columns[$i] == "brand_id"){
                if ($i == sizeof($columns)-1) {
                    
                    if (is_array($variables[$i])) {
                        
                        $where .= "($columns[$i] IN (";

                        for ($v=0; $v < sizeof($variables[$i]); $v++) { 
                            if ($v == sizeof($variables[$i])-1) {
                                $where .= "\"".$variables[$i][$v]."\"))";
                            }else{
                                $where .= "\"".$variables[$i][$v]."\",";
                            }
                        }
                        
                    }else{
                        //$where .= "($columns[$i] = \"$variables[$i]\")";
                        $where .= "( $columns[$i] IN ('9','13','14','15','16')) AND";
                    }
                }else{
                    if (is_array($variables[$i])) {

                        $where .= "($columns[$i] IN (";
                        
                        for ($v=0; $v < sizeof($variables[$i]); $v++) { 
                            if ($v == sizeof($variables[$i])-1) {
                                $where .= "\"".$variables[$i][$v]."\")) AND ";
                            }else{
                                $where .= "\"".$variables[$i][$v]."\",";
                            }
                        }
                    }else{
                        //$where .= "($columns[$i] = \"$variables[$i]\")";
                        $where .= "( $columns[$i] IN ('9','13','14','15','16')) AND";
                    }
                }
            }else{
                if ($i == sizeof($columns)-1) {
                    
                    if (is_array($variables[$i])) {
                        
                        $where .= "($columns[$i] IN (";

                        for ($v=0; $v < sizeof($variables[$i]); $v++) { 
                            if ($v == sizeof($variables[$i])-1) {
                                $where .= "\"".$variables[$i][$v]."\"))";
                            }else{
                                $where .= "\"".$variables[$i][$v]."\",";
                            }
                        }
                        
                    }else{
                        $where .= "($columns[$i] = \"$variables[$i]\")";
                    }
                }else{
                    if (is_array($variables[$i])) {

                        $where .= "($columns[$i] IN (";
                        
                        for ($v=0; $v < sizeof($variables[$i]); $v++) { 
                            if ($v == sizeof($variables[$i])-1) {
                                $where .= "\"".$variables[$i][$v]."\")) AND ";
                            }else{
                                $where .= "\"".$variables[$i][$v]."\",";
                            }
                        }
                    }else{
                        $where .= "($columns[$i] = \"$variables[$i]\") AND ";
                    }
                }
            }
        }
        
        return $where;

    }

    public function deleteValues($con,$table,$where){
        $sql = "DELETE FROM $table $where";

        if($con->query($sql) === true){
            $rtr["bool"] = true;
            $rtr["msg"] = "Successfully updated!";
        }else{
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: ".$sql."<br>".$con->error;
        }


        return $rtr;

    }

}
