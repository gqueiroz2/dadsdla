<table>
	<tr>
		<th style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="27">{{$data['forRender']['salesRep']['salesRep']}} - {{$data['forRender']['currencyName']}} / {{$data['forRender']['valueView']}} - {{$data['userRegion']}} </th>
	</tr>
</table>

 <table>
    <tr>
        <td style=" background-color: #0f243e; color: #FFFFFF; font-weight: bold; ">{{$data['forRender']['salesRep']['abName']}}</td>
        @for($m=0; $m < sizeof($data['month']); $m++)
            @if($m == 3 || $m == 7 || $m == 11 || $m == 15)
                <td style="background-color: #4f81bd; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @else
                <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
            <td style=" background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Total</td>
            <td></td>
            <td style="background-color: #a6a6a6; font-weight: bold;">Closed</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Cons. (%)</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Exp</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Prop</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Adv</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Contr</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Total</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Lost</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;">Target</td>
            @for($m=0; $m < sizeof($data['month']); $m++)
                @if($m == 3 || $m == 7 || $m == 11 || $m == 15)
                    <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['targetValues'][$m]}}</td>
                @else
                    <td style="background-color: #bfbfbf; font-weight: bold;">{{$data['forRender']['targetValues'][$m]}}</td>
                @endif
            @endfor
            <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['targetValues'][$m]}}</td>
            <td></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
            <td style="background-color: #dce6f1;"></td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Rolling Fcast {{$data['cYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['executiveRF'][$m]}}</td>
            @else
                <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['executiveRF'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['executiveRF'][$m]}}</td>
        <td></td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][4]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][7]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][0]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][1]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][2]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][3]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][6]}}</td>
        <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['fcstAmountByStageEx'][1][5]}}</td>
    </tr>
    <tr>
        <td style=" background-color: #dce6f1; font-weight: bold;">Bookings</td>
        @for ($m=0; $m < sizeof ($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['executiveRevenueCYear'][$m]}}</td>
            @else
                <td style="background-color: #bfbfbf; font-weight: bold;">{{$data['forRender']['executiveRevenueCYear'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['executiveRevenueCYear'][$m]}}</td>
        <td></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Pending</td>
        @for ($m=0; $m < sizeof ($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['pending'][$m]}}</td>
            @else
                <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['pending'][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['pending'][$m]}}</td>
        <td></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
    </tr>
    <tr>
        <td style=" background-color: #dce6f1; font-weight: bold; text-align: left;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof ($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['executiveRevenuePYear'][$m]}}</td>
            @else
                <td style="background-color: #bfbfbf; font-weight: bold;">{{$data['forRender']['executiveRevenuePYear'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['executiveRevenuePYear'][$m]}}</td>
        <td></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Var RF vs Target</td>
        @for ($m=0; $m < sizeof ($data['month']) ; $m++)  
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['RFvsTarget'][$m]}}</td>
            @else
                <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['RFvsTarget'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['RFvsTarget'][$m]}}</td>
        <td></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
    </tr>
    <tr>
        <td style=" background-color: #dce6f1; font-weight: bold;">% Target Achievement</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],2,',','.')}}%</td>
            @else
                <td style="background-color: #bfbfbf; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],2,',','.')}}%</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],2,',','.')}}%</td>   
        <td></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
    </tr>
</table>
@for ($c=0; $c < sizeof($data['forRender']['client']); $c++)
<table>
    <tr>   
        <td style="background-color: #0070c0; font-weight: bold; color:#FFFFFF;">{{$data['forRender']['nSecondary'][$c]['clientName']}} -  {{$data['forRender']['nSecondary'][$c]["agencyName"]}} {{$data['ow']}}</td>
        @for($m=0; $m < sizeof($data['month']); $m++)
            @if($m == 3 || $m == 7 || $m == 11 || $m == 15)
                <td style="background-color: #4f81bd; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @else
                <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
            <td style=" background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Total</td>
            <td></td>
            <td style="background-color: #a6a6a6; font-weight: bold;">Closed</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Cons. (%)</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Exp</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Prop</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Adv</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Contr</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Total</td>
            <td style="background-color: #a6a6a6; font-weight: bold; ">Lost</td>
    </tr>
    <tr>
        <td  style="font-weight: bold; background-color: #dce6f1;"> Rolling Fcast {{$data['cYear']}}</td>
         @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['lastRollingFCST'][$m]}}</td>
            @else
                <td style="background-color: #bfbfbf; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['lastRollingFCST'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['nSecondary'][$c]['lastRollingFCST'][$m]}}</td>
        <td></td>
        @if($data['forRender']['nSecondary'][$c]['fcstAmountByStage'])
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][4]}}</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][7]}}%</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][0]}}</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][1]}}</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][2]}}</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][3]}}</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][6]}}</td>
            <td style=" font-weight: bold; background-color: #dce6f1;">{{$data['forRender']['nSecondary'][$c]['fcstAmountByStage'][1][5]}}</td>
        @else
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
            <td style=" font-weight: bold; background-color: #dce6f1;">%</td>
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
            <td style=" font-weight: bold; background-color: #dce6f1;"></td>
        @endif
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #e7eff9;">Manual Estimation</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['rollingFCST'][$m]}}</td>
            @else
                <td style="background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['rollingFCST'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['nSecondary'][$c]['rollingFCST'][$m]}}</td>
        <td></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #dce6f1;">Booking</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['clientRevenueCYear'][$m]}}</td>
            @else
                <td style=" background-color: #bfbfbf; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['clientRevenueCYear'][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['nSecondary'][$c]['clientRevenueCYear'][$m]}}</td>
        <td></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>  
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #e7eff9; text-align: left;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['clientRevenuePYear'][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold;">{{$data['forRender']['nSecondary'][$c]['clientRevenuePYear'][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['forRender']['nSecondary'][$c]['clientRevenuePYear'][$m]}}</td>
        <td></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
        <td style="background-color: #e6e6e6;"></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #dce6f1;">Var RF vs {{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['nSecondary'][$c]['rollingFCST'][$m] - $data['forRender']['nSecondary'][$c]['clientRevenuePYear'][$m]}}</td>
            @else
                <td style=" background-color: #bfbfbf; font-weight: bold; text-align: right;">{{$data['forRender']['nSecondary'][$c]['rollingFCST'][$m] - $data['forRender']['nSecondary'][$c]['clientRevenuePYear'][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #FFFFFF; text-align: right;">{{$data['forRender']['nSecondary'][$c]['rollingFCST'][$m] - $data['forRender']['nSecondary'][$c]['clientRevenuePYear'][$m]}}</td>
        <td></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
        <td style="background-color: #dce6f1;"></td>
    </tr>
</table>
@endfor