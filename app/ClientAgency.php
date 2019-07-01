<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\excelBasics;
class ClientAgency extends excelBasics{
    public function handler($type,$param = false){
      if($param){
        $name = ucfirst($type);
      }else{
        $name =$type;
      }
      $tg = $name."_group";
      $t = $name;
      $tu = $name."_unit";
      $sheets = array($tg,$t,$tu);
      return $sheets;
    }
    public function toDataBase($con,$table,$spreadSheet,$base){
      var_dump($table);
      $check = 0;
      for ($t=0; $t < sizeof($table); $t++) { 
        $bool = $this->eachOne($con,$table[$t],$spreadSheet[$t],$base);
        if($bool){
          $check++;
        }
      }
      if($check == sizeof($table)){
        $rtr = true;
      }else{
        $rtr = false;
      }
      return $rtr;
    }
    public function eachOne($con,$table,$spreadSheet,$base){
    
      $columns = $this->defineColumns($table);
      var_dump($columns);
      $into = $this->into($columns);      
      $spreadSheet = $this->assembler($spreadSheet,$columns,$base);
    
    array_multisort(array_column($spreadSheet, "ID"),SORT_ASC,$spreadSheet);
    $check = 0;
        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $error = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);         
            var_dump($error);
            if(!$error){
                $check++;
            }
        } 
      if($check == sizeof($spreadSheet)){
            $complete = true;
        }else{
            $complete = false;
        }
        return $complete;
    }
    public function insert($con,$spreadSheet,$columns,$table,$into,$nextColumns = false){
        
        $values = $this->values($spreadSheet,$columns);
        $ins = " INSERT INTO $table ($into) VALUES ($values)"; 
        var_dump($ins);
        if($con->query($ins) === TRUE ){
            $error = false;
        }else{
            echo "<pre>".($ins)."</pre>";
            var_dump($con->error);
            $error = true;
        }     
        return $error;        
    }
    public function defineColumns($table,$recurrency = false){
      $temp = explode("_", $table);
      if( !isset($temp[1]) ){
        if($temp[0] == 'agency'){
          $rtr = $this->columnsA;
        }else{
          $rtr = $this->columnsC;
        }
      }elseif( $temp[1] == 'group' ){
        if($temp[0] == 'agency'){
          $rtr = $this->columnsAG;
        }else{
          $rtr = $this->columnsCG;
        }
      }elseif( $temp[1] == 'unit' ){
        if($temp[0] == 'agency'){
          $rtr = $this->columnsAU;
        }else{
          $rtr = $this->columnsCU;
        }
      }else{
        $rtr = false;
      }
      
      return $rtr;  
      
    }
    protected $columnsAG = array("ID","region_id","name");
    protected $columnsA = array("ID","agency_group_id","name");
    protected $columnsAU = array("ID","agency_id","origin_id","name");
    protected $columnsCG = array("ID","region_id","name");
    protected $columnsC = array("ID","client_group_id","name");
    protected $columnsCU = array("ID","client_id","origin_id","name");
}