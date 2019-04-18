<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class dataManagementRender extends Render{
    
    public function editOrigin($origin){
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

	public function editRegion($region){
        echo "<div class='row mt-1'>";

            echo "<div class='col'> Region </div>";          
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
    	for ($r=0; $r < sizeof($region); $r++) { 
    		
    		echo "<div class='row mt-1'>";

        		echo "<div class='col'>";
        			echo "<input type='text' readonly='true' class='form-control' value='".$region[$r]["name"]."' style='width:100%;'>";
        		echo "</div>";

    		echo "</div>";

    	}

    }

    public function editRegion2($region){
        echo "<div class='row mt-1'>";

            echo "<div class='col'> Old Name </div>";          
            echo "<div class='col'> New Name </div>";                

        echo "</div>";
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

    public function editCurrency($currency){
        echo "<div class='row mt-1'>";

            echo "<div class='col'> Region </div>";
            echo "<div class='col'> Currency </div>";  

        echo "</div>";
        for ($c=0; $c < sizeof($currency); $c++) { 
            
            echo "<div class='row mt-1'>";

                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$currency[$c]["region"]."' style='width:100%;'>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='text' readonly='true' class='form-control' value='".$currency[$c]["name"]."' style='width:100%;'>";
                echo "</div>";

            echo "</div>";

        }

    }

    public function editPRate($pRate){
        echo "<div class='row mt-1'>";

            echo "<div class='col'> Region </div>";
            echo "<div class='col'> Currency </div>";
            echo "<div class='col'> Year </div>";
            echo "<div class='col'> Value </div>";                
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";


        for ($p=0; $p < sizeof($pRate); $p++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$pRate[$p]["region"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$pRate[$p]["currency"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$pRate[$p]["year"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='". number_format( $pRate[$p]["value"] ,2 ) ."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }
    }

    public function editSalesRepGroup($salesRepGroup){

        echo "<div class='row mt-1'>";

            echo "<div class='col'> Region </div>";
            echo "<div class='col'> Sales Rep. Group </div>";              
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
        
        for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRepGroup[$s]["region"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRepGroup[$s]["name"]."' style='width:100%;'>";
            echo "</div>";

            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }

    }

    public function editSalesRep($salesRep){

        echo "<div class='row mt-1'>";

            echo "<div class='col'> Region </div>";
            echo "<div class='col'> Sales Rep. Group </div>";              
            echo "<div class='col'> Sales Rep </div>";              
            echo "<div class='col'> &nbsp; </div>";                

        echo "</div>";
        
        for ($s=0; $s < sizeof($salesRep); $s++) { 
            
            echo "<div class='row mt-1'>";

            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRep[$s]["region"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRep[$s]["salesRepGroup"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='text' readonly='true' class='form-control' value='".$salesRep[$s]["salesRep"]."' style='width:100%;'>";
            echo "</div>";
            echo "<div class='col'>";
                echo "<input type='button' class='btn btn-primary' style='width:100%;' value='Edit'>";
            echo "</div>";

            echo "</div>";

        }

    }

    public function editSalesRepUnit($salesRepUnit){

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

    public function editBrand($brand){

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

    public function editBrandUnit($brandUnit){

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

    public function editUserType($userType){

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

    public function editUser($user){
        
        
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

}
