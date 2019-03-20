<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\results;

class monthly extends results{
    
    public function caller($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth){
        
        $this->assembler($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth);



    }

}
