<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class dataManagementRender extends Render{
    
    public function originEdit($origin){
        echo "<div class='row mt-1'>";

            echo "<div class='col'> Origin </div>";          
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
        for ($o=0; $o < sizeof($origin); $o++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$origin[$o]["name"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }
    }

    public function regionEdit($region){
        echo "<div class='row mt-1'>";

            echo "<div class='col'> Old Name </div>";          
            echo "<div class='col'> New Name </div>";                

        echo "</div>";

        echo "<input type='hidden' name='size' value='".sizeof($region)."'>";

        for ($r=0; $r < sizeof($region); $r++) { 
            
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    echo "<input type='text' name='Old-$r' readonly='true' class='form-control' value='".$region[$r]["name"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' name='New-$r' class='form-control' value='".$region[$r]["name"]."' style='width:100%;'>";
                echo "</div>";
            
            echo "</div>";

        }

    }


    public function currencyEdit($currency,$region){
        echo "<div class='row mt-1'>";
            echo "<div class='col'> Old Region </div>";
            echo "<div class='col'> Old Currency </div>";  
            echo "<div class='col'> New Region </div>";
            echo "<div class='col'> New Currency </div>";  
        echo "</div>";

        echo "<input type='hidden' name='size' value='".sizeof($currency)."'>";
        for ($c=0; $c < sizeof($currency); $c++) { 
            
            echo "<div class='row mt-1'>";
                echo "<div class='col'>";
                    echo "<input type='text' name='OldRegion-$c' readonly='true' class='form-control' value='".$currency[$c]["region"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' name='OldName-$c' readonly='true' class='form-control' value='".$currency[$c]["name"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<select class='form-control' name='NewRegion-$c'>";
                    for($r = 0; $r < sizeof($region);$r++){
                        if ($region[$r]["name"] == $currency[$c]["region"]) {
                            echo "<option selected='true' value='".$region[$r]["name"]."'>" ;
                                echo $region[$r]["name"];  
                            echo "</option>";
                        }else{
                            echo "<option value='".$region[$r]["name"]."'>" ;
                                echo $region[$r]["name"];  
                            echo "</option>";
                        }
                    } 
                    echo "</select>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' name='NewName-$c' class='form-control' value='".$currency[$c]["name"]."' style='width:100%;'>";
                echo "</div>";

            echo "</div>";

        }

    }

    public function pRateEdit($pRate){
        echo "<div class='row mt-1'>";
            echo "<div class='col'> Region </div>";
            echo "<div class='col'> Currency </div>";
            echo "<div class='col'> Old Year </div>";
            echo "<div class='col'> Old Value </div>";                
            echo "<div class='col'> New Year </div>";
            echo "<div class='col'> New Value </div>";                
        echo "</div>";

        echo "<input type='hidden' name='size' value='".sizeof($pRate)."'>";

        for ($p=0; $p < sizeof($pRate); $p++) { 
            
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' name='region-$p' class='form-control' value='".$pRate[$p]["region"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' name='currency-$p' class='form-control' value='".$pRate[$p]["currency"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' name='oldYear-$p' class='form-control' value='".$pRate[$p]["year"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' name='oldValue-$p' class='form-control' value='". number_format( $pRate[$p]["value"] ,5 ) ."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='number' class='form-control' name='newYear-$p' value='".$pRate[$p]["year"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='number' class='form-control' name='newValue-$p' value='". number_format( $pRate[$p]["value"] ,5,'.','') ."' style='width:100%;' step='0.000001'>";
                echo "</div>";

            echo "</div>";

        }
    }

    public function salesRepGroupEdit($salesRepGroup,$region){

        echo "<div class='row mt-1'>";

            echo "<div class='col'> Old Region </div>";
            echo "<div class='col'> Old Sales Rep. Group </div>";              
            echo "<div class='col'> New Region </div>";
            echo "<div class='col'> New Sales Rep. Group </div>";              

        echo "</div>";
        
        echo "<input type='hidden' name='size' value='".sizeof($salesRepGroup)."'>"; 
    
        for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
               
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    for($r = 0; $r < sizeof($region);$r++){
                        if ($region[$r]["name"] == $salesRepGroup[$s]["region"]) {
                            echo "<input type='hidden' name='oldRegion-$s' readonly='true' class='form-control' value='".$region[$r]["id"]."' style='width:100%;'>";
                        }   
                    }
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRepGroup[$s]["region"]."' style='width:100%;'>";
                echo "</div>";
                
                echo "<div class='col'>";
                    echo "<input type='text' name='oldName-$s' readonly='true' class='form-control' value='".$salesRepGroup[$s]["name"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<select class='form-control' name='newRegion-$s'>";
                    for($r = 0; $r < sizeof($region);$r++){
                        if ($region[$r]["name"] == $salesRepGroup[$s]["region"]) {
                            echo "<option selected='true' value='".$region[$r]["id"]."'>" ;
                                echo $region[$r]["name"];  
                            echo "</option>";
                        }else{
                            echo "<option value='".$region[$r]["id"]."'>" ;
                                echo $region[$r]["name"];  
                            echo "</option>";
                        }
                    } 
                    echo "</select>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' name='newName-$s' class='form-control' value='".$salesRepGroup[$s]["name"]."' style='width:100%;'>";
                echo "</div>";


            echo "</div>";
            
        }

    }

    public function salesRepEdit($salesRep,$region,$salesGroup){
        echo "<div class='row mt-1'>";
            
            echo "<div class='col'> Region </div>";              
            echo "<div class='col'> Old Sales Rep. Group </div>";              
            echo "<div class='col'> Old Sales Rep. Name </div>";              
            echo "<div class='col'> New Sales Rep. Group </div>";              
            echo "<div class='col'> New Sales Rep. Name </div>";              

        echo "</div>";

        echo "<input type='hidden' value='".sizeof($salesRep)."' name='size'>";

        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
            echo "<div class='row mt-1'>";
                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($region) ; $i++) { 
                        if ($region[$i]["name"] == $salesRep[$s]["region"]) {
                            echo "<input type='hidden' name='region-$s' class='form-control' value='".$region[$i]["id"]."'     style='width:100%;'>";
                        }
                    }
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRep[$s]["region"]."' >";
                echo "</div>";
                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($region) ; $i++) { 
                        if ($region[$i]["name"] == $salesRep[$s]["region"]) {
                            for ($j=0; $j <sizeof($salesGroup[$region[$i]["name"]]) ; $j++) { 
                                if ($salesGroup[$region[$i]["name"]][$j]["name"] == $salesRep[$s]["salesRepGroup"]) {
                                    echo "<input type='hidden' class='form-control' name='oldSalesGroup-$s' value='".$salesGroup[$region[$i]["name"]][$j]["id"]."' >";
                                }
                            }
                        }
                    }
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRep[$s]["salesRepGroup"]."' >";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' name='oldSalesRep-$s' class='form-control' value='".$salesRep[$s]["salesRep"]."' >";
                echo "</div>";
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
                echo "<div class='col'>";
                    echo "<select class='form-control' name='newSalesGroup-$s'>";
                        for ($i=0; $i <sizeof($region) ; $i++) { 
                            if ($region[$i]["name"] == $salesRep[$s]["region"]) {
                                for ($j=0; $j <sizeof($salesGroup[$region[$i]["name"]]) ; $j++) { 
                                    if ($salesGroup[$region[$i]["name"]][$j]["name"] == $salesRep[$s]["salesRepGroup"]) {
                                        echo "<option value=".$salesGroup[$region[$i]["name"]][$j]["id"]." selected='true'>".$salesGroup[$region[$i]["name"]][$j]["name"]."</option>";
                                    }else{
                                        echo "<option value=".$salesGroup[$region[$i]["name"]][$j]["id"].">".$salesGroup[$region[$i]["name"]][$j]["name"]."</option>";
                                    }
                                }
                            }
                        }
                    echo "</select>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' name='newSalesRep-$s' class='form-control' value='".$salesRep[$s]["salesRep"]."' >";
                echo "</div>";
            echo "</div>";
        }

    }

    public function salesRepUnitEdit($salesRepUnit,$salesRep,$origin){

        echo "<div class='row mt-1'>";
            
            echo "<div class='col'> Sales Rep. </div>";              
            echo "<div class='col'> Old Sales Rep. Unit </div>";              
            echo "<div class='col'> Old Origin </div>";              
            echo "<div class='col'> New Sales Rep. Unit </div>";                
            echo "<div class='col'> New Origin </div>";                

        echo "</div>";

        echo "<input type='hidden' name='size' value='".sizeof($salesRepUnit)."'>";
        
        for ($s=0; $s < sizeof($salesRepUnit); $s++) { 
            
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($salesRep) ; $i++) { 
                        if ($salesRep[$i]["salesRep"] == $salesRepUnit[$s]["salesRep"]) {
                            echo "<input type='hidden' value='".$salesRep[$i]["id"]."' name='salesRep-$s' >";
                        }
                    }
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRepUnit[$s]["salesRep"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' name='oldSalesRepUnit-$s' readonly='true' class='form-control' value='".$salesRepUnit[$s]["salesRepUnit"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($origin) ; $i++) { 
                        if($origin[$i]["name"] == $salesRepUnit[$s]["origin"]){
                            echo "<input type='hidden' value='".$origin[$i]["id"]."' name='oldOrigin-$s' >";
                        }
                    }
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRepUnit[$s]["origin"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' name='newSalesRepUnit-$s' class='form-control' value='".$salesRepUnit[$s]["salesRepUnit"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<select class='form-control' name='newOrigin-$s'>";
                        for ($i=0; $i <sizeof($origin); $i++) { 
                            if($origin[$i]["name"] == $salesRepUnit[$s]["origin"]){
                                echo "<option selected='true' value='".$origin[$i]["id"]."'>".$origin[$i]["name"]."</option>";
                            }else{
                                echo "<option value='".$origin[$i]["id"]."'>".$origin[$i]["name"]."</option>";
                            }
                        }
                    echo "</select>";
                echo "</div>";
            echo "</div>";

        }
    }

    public function brandEdit($brand){

        echo "<div class='row mt-1'>";
            
            echo "<div class='col'> Brand </div>";                                      
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
        
        for ($b=0; $b < sizeof($brand); $b++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$brand[$b]["name"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }
    }

    public function brandUnitEdit($brandUnit){

        echo "<div class='row mt-1'>";
            
            echo "<div class='col'> Brand </div>";                                      
            echo "<div class='col'> Origin </div>";                                      
            echo "<div class='col'> Brand Unit </div>";                                      
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
        
        for ($b=0; $b < sizeof($brandUnit); $b++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$brandUnit[$b]["brand"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$brandUnit[$b]["origin"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$brandUnit[$b]["brandUnit"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }

    }

    public function userTypeEdit($userType){

        echo "<div class='row mt-1'>";
            
            echo "<div class='col'> Old User Type </div>";                                   
            echo "<div class='col'> Old Level </div>";                           
            echo "<div class='col'> New User Type </div>";                                   
            echo "<div class='col'> New Level </div>";                           

        echo "</div>";

        echo "<input type='hidden' name='size' value='".sizeof($userType)."'>";
        
        for ($u=0; $u < sizeof($userType); $u++) { 
            
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    echo "<input type='text' name='oldUserType-$u' readonly='true' class='form-control' value='".$userType[$u]["name"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' name='oldLevel-$u' readonly='true' class='form-control' value='".$userType[$u]["level"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' name='newUserType-$u' class='form-control' value='".$userType[$u]["name"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' name='newLevel-$u' class='form-control' value='".$userType[$u]["level"]."' style='width:100%;'>";
                echo "</div>";            

            echo "</div>";

        }
    }   

    public function userEdit($user,$region,$userType,$salesGroup){
        
        echo "<input type='hidden' name='size' value='".sizeof($user)."'>";
        
        for ($u=0; $u < sizeof($user); $u++) { 
            
            echo "<hr>";
                

            echo "<p style='text-align:center;'><b> USER # ".$user[$u]['id']."</b></p>";

            echo "<hr>";

            echo "<div class='row mt-1'>";            
                echo "<div class='col'> Name </div>";
                echo "<div class='col'> Region </div>";                           
                echo "<div class='col'> Status </div>";                                   
            echo "</div>";    

            echo "<div class='row mt-1'>";
                echo "<div class='col'>";
                    echo "<input type='hidden' name='oldName-$u' class='form-control' value='".$user[$u]["name"]."' style='width:100%;'>";
                    echo "<input type='text' name='newName-$u' class='form-control' value='".$user[$u]["name"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($region) ; $i++) { 
                        if ($region[$i]["name"] == $user[$u]["region"]) {
                            echo "<input type='hidden' name='oldRegion-$u' class='form-control' value='".$region[$i]["id"]."'     style='width:100%;'>";
                        }
                    }
                    echo "<select name='newRegion-$u' class='form-control' style='width:100%;'>";
                        for ($i=0; $i <sizeof($region) ; $i++) { 
                            if ($region[$i]["name"] == $user[$u]["region"]) {
                                echo "<option selected='true' value ='".$region[$i]["id"]."'>".$region[$i]["name"]."</option>";
                            }else{
                                echo "<option  value ='".$region[$i]["id"]."'>".$region[$i]["name"]."</option>";
                            }
                        }
                    echo "</select>";
                echo "</div>";                
                echo "<div class='col'>";
                    echo "<input type='hidden' name='oldStatus-$u' class='form-control' value='".$user[$u]["status"]."' style='width:100%;'>";
                    echo "<select name='newStatus-$u' class='form-control' style='width:100%;'>";
                        if ($user[$u]["status"] == 1) {
                            echo "<option selected='true' value ='1' >Enable</option>";
                            echo "<option value ='0'>Disable</option>";
                        }else{
                            echo "<option value ='1' >Enable</option>";
                            echo "<option selected='true' value ='0'>Disable</option>";
                        }                            
                        
                    echo "</select>";
                echo "</div>";
            echo "</div>";

            echo "<div class='row mt-1'>";           
                        
                echo "<div class='col'> Sub Level Bool </div>";   
                echo "<div class='col'> User Type </div>";                                   
                echo "<div class='col'> Sub Rep. Group </div>";                         
            echo "</div>";

            echo "<div class='row mt-1'>";
                
                echo "<div class='col'>";
                    echo "<input type='hidden' name='oldSubLevelBool-$u' class='form-control' value='".$user[$u]["subLevelBool"]."' style='width:100%;'>";
                    echo "<select name='newSubLevelBool-$u' class='form-control' style='width:100%;'>";
                        if ($user[$u]["subLevelBool"] == 1) {
                            echo "<option selected='true' value ='1' >Yes</option>";
                            echo "<option value ='0'>No</option>";
                        }else{
                            echo "<option value ='1' >Yes</option>";
                            echo "<option selected='true' value ='0'>No</option>";
                        }                            
                        
                    echo "</select>";
                echo "</div>";
                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($userType) ; $i++) { 
                        if ($userType[$i]['name'] == $user[$u]["userType"]) {
                            echo "<input type='hidden' name='oldUserType-$u' class='form-control' value='".$userType[$i]['id']."' style='width:100%;'>";
                        }
                    }
                    echo "<select name='newUserType-$u' class='form-control' style='width:100%;'>";
                        for ($i=0; $i <sizeof($userType) ; $i++) { 
                            if ($userType[$i]['name'] == $user[$u]["userType"]) {
                                echo "<option selected='true' value ='".$userType[$i]["id"]."'>".$userType[$i]["name"]."</option>";
                            }else{
                                echo "<option value ='".$userType[$i]["id"]."'>".$userType[$i]["name"]."</option>";
                            }
                        }
                    echo "</select>";
                echo "</div>";
                echo "<div class='col'>";
                    for ($i=0; $i <sizeof($salesGroup[$user[$u]["region"]]) ; $i++) { 
                        if ($user[$u]["salesRepGroup"] == $salesGroup[$user[$u]["region"]][$i]["name"]) {
                            echo "<input type='hidden' name='oldSalesGroup-$u' class='form-control' value='".$salesGroup[$user[$u]["region"]][$i]["id"]."' style='width:100%;'>";
                        }
                    }
                    echo "<select name='newSalesGroup-$u' class='form-control' style='width:100%;'>";
                        for ($i=0; $i <sizeof($salesGroup[$user[$u]["region"]]) ; $i++) { 
                            if ($user[$u]["salesRepGroup"] == $salesGroup[$user[$u]["region"]][$i]["name"]) {
                                echo "<option selected='true' value ='".$salesGroup[$user[$u]["region"]][$i]["id"]."'>".$salesGroup[$user[$u]["region"]][$i]["name"]."</option>";
                            }else{
                                echo "<option value ='".$salesGroup[$user[$u]["region"]][$i]["id"]."'>".$salesGroup[$user[$u]["region"]][$i]["name"]."</option>";
                            }
                        }
                    echo "</select>";
                echo "</div>";
            echo "</div>";


            echo "<hr>";

        }
    }


    public function filters($region){
    
        echo "<div class='row'>";
            echo "<div class='col col-sm-9'> Region </div>";
        echo "</div>";
        echo "<div class='row mt-1'>";
            echo "<div class='col col-sm-9'>";
                echo "<select class = 'form-control' name='filterRegion'>";
                    echo "<option value=''> None </option>";
                    for ($i=0; $i <sizeof($region); $i++) { 
                        echo "<option value = \"".$region[$i]["id"]."\">".$region[$i]["name"]."</option>";
                    }
                echo "</select>";
            echo "</div>";
            echo "<div class='col col-sm-3'>";
                echo "<input type='submit' class='btn btn-primary' value='Filter' style=\"width: 100%;\">";
            echo "</div>";
        echo "</div>";
    }

    public function filterBySalesRep($salesRep){
        echo "<div class='row'>";
            echo "<div class='col col-sm-9'> Sales Rep. </div>";
        echo "</div>";
        echo "<div class='row mt-1'>";
            echo "<div class='col col-sm-9'>";
                echo "<select class = 'form-control' name='filterRep'>";
                    echo "<option value=''> None </option>";
                    for ($i=0; $i <sizeof($salesRep); $i++) { 
                        echo "<option value = \"".$salesRep[$i]["id"]."\">".$salesRep[$i]["salesRep"]."</option>";
                    }
                echo "</select>";
            echo "</div>";
            echo "<div class='col col-sm-3'>";
                echo "<input type='submit' class='btn btn-primary' value='Filter' style=\"width: 100%;\">";
            echo "</div>";
        echo "</div>";
    }


}
