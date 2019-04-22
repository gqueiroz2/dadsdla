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
                    echo "<input type='text' class='form-control' name='newYear-$p' value='".$pRate[$p]["year"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' class='form-control' name='newValue-$p' value='". number_format( $pRate[$p]["value"] ,5 ) ."' style='width:100%;'>";
                echo "</div>";

            echo "</div>";

        }
    }

    public function salesRepEdit($salesRepGroup,$region){

        echo "<div class='row mt-1'>";

            echo "<div class='col'> Old Region </div>";
            echo "<div class='col'> Old Sales Rep. Group </div>";              
            echo "<div class='col'> New Region </div>";
            echo "<div class='col'> New Sales Rep. Group </div>";              

        echo "</div>";
        /*
        for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
            
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRepGroup[$s]["region"]."' style='width:100%;'>";
                echo "</div>";
                
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$salesRepGroup[$s]["name"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<select class='form-control' name='NewRegion-$s'>";
                    for($r = 0; $r < sizeof($region);$r++){
                        if ($region[$r]["name"] == $salesRepGroup[$s]["region"]) {
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
                    echo "<input type='text' class='form-control' value='".$salesRepGroup[$s]["name"]."' style='width:100%;'>";
                echo "</div>";


            echo "</div>";
            
        }*/

    }

    public function salesRepUnitEdit($salesRepUnit){

        echo "<div class='row mt-1'>";
            
            echo "<div class='col'> Sales Rep. </div>";              
            echo "<div class='col'> Sales Rep. Unit </div>";              
            echo "<div class='col'> Origin </div>";              
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
        
        for ($s=0; $s < sizeof($salesRepUnit); $s++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRepUnit[$s]["salesRep"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRepUnit[$s]["salesRepUnit"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRepUnit[$s]["origin"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
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
            
            echo "<div class='col'> User Type </div>";                                   
            echo "<div class='col'> Level </div>";                           

        echo "</div>";
        
        for ($u=0; $u < sizeof($userType); $u++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$userType[$u]["name"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$userType[$u]["level"]."' style='width:100%;'>";
            echo "</div>";            

            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }
    }   

    public function userEdit($user){
        
        
        for ($u=0; $u < sizeof($user); $u++) { 
            
            echo "<hr>";
                

            echo "<p style='text-align:center;'><b> USER # ".$user[$u]['id']."</b></p>";

            echo "<hr>";

            echo "<div class='row mt-1'>";            
                echo "<div class='col'> Name </div>";                                   
                echo "<div class='col'> E-mail </div>";             
                echo "<div class='col'> Password </div>";                                                 
            echo "</div>";    

            echo "<div class='row mt-1'>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["name"]."' style='width:100%;'>";
                echo "</div>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["email"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["password"]."' style='width:100%;'>";
                echo "</div>";
            echo "</div>";

            echo "<div class='row mt-1'>";           
                echo "<div class='col'> Region </div>";                           
                echo "<div class='col'> Status </div>";                           
                echo "<div class='col'> Sub Level Bool </div>";                           
            echo "</div>";

            echo "<div class='row mt-1'>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["region"]."' style='width:100%;'>";
                echo "</div>";                
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["status"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["subLevelBool"]."' style='width:100%;'>";
                echo "</div>";
            echo "</div>";

            echo "<div class='row mt-1'>";            
                echo "<div class='col'> User Type </div>";                                   
                echo "<div class='col'> Level </div>";                           
                echo "<div class='col'> Sub Rep. Group </div>";                                                   
            echo "</div>";

            echo "<div class='row mt-1'>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["userType"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["level"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$user[$u]["salesRepGroup"]."' style='width:100%;'>";
                echo "</div>";
            echo "</div>";

            echo "<hr>";

        }

        echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";
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

}
