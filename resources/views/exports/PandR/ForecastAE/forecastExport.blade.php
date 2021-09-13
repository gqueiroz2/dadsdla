<table>
	<tr>
		<td colspan="28" style="background-color: #0070c0; color: #ffffff; font-weight: bold; text-align: left;">{{$data['forRender']['salesRep']['salesRep']}} - {{$data['forRender']['currencyName']}}/{{$data['forRender']['valueView']}}</td>
	</tr>
</table>

<td></td>

<table>
	<tr>
		<td></td>
		<td style=" background-color: #0f243e; color: #ffffff; font-weight: bold; text-align: left;">{{$data['forRender']['salesRep']['abName']}}</td>
		@for($m=0; $m < sizeof($data['month']); $m++)
			@if($m == 3 || $m == 7 || $m == 11 || $m == 15)
				<td style="background-color: #143052; color: #ffffff;font-weight: bold; text-align: right;">{{$data['month'][$m]}}</td>
			@else
				<td style="background-color: #4f81bd; color: #ffffff; font-weight: bold; text-align: right;">{{$data['month'][$m]}}</td>
			@endif
		@endfor
		<td style=" background-color: #0f243e; color: #ffffff; font-weight: bold; text-align: right;">Total</td>
		<td></td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Closed</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Cons. (%)</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Exp</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Prop</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Adv</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Contr</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Total</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Lost</td>
	</tr>

	<tr>
		<td></td>
		<td style="background-color: #c8d8e9;
		font-weight: bold; ">Target</td>
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['targetValues'][$m]}}</td>
		   @else
		   		<td style="background-color: #f2f2f2; font-weight: bold; text-align: right;">{{$data['forRender']['targetValues'][$m]}}</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['forRender']['targetValues'][$m]}}</td>
		<td></td>
		@for($i=0; $i < 8; $i++)
			<td style="background-color: #c8d8e9;
		font-weight: bold; text-align: right;"></td>
		@endfor
	</tr>
	<tr>
		<td></td>
		<td style="background-color: #e7eff9;
		font-weight: bold; ">Rolling Fcast {{$data['cYear']}}</td>	
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRF'][$m]}}</td>
		   @else
		   		<td style="background-color: #e7eff9; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRF'][$m]}}</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRF'][$m]}}</td>
		<td></td>
		<td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][4]}} </td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{number_format($data['forRender']['fcstAmountByStageEx'][1][7],0)}} %</td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][0]}} </td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][1]}} </td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][2]}} </td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][3]}} </td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][6]}} </td>
        <td style="background-color: #e7eff9; font-weight: bold; text-align: right;"> {{$data['forRender']['fcstAmountByStageEx'][1][5]}} </td>
	</tr>
	<tr>
		<td></td>
		<td style="background-color: #c8d8e9;
		font-weight: bold; ">Bookings</td>
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRevenueCYear'][$m]}}</td>
		   @else
		   		<td style="background-color: #f2f2f2; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRevenueCYear'][$m]}}</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRevenueCYear'][$m]}}</td>
		<td></td>
		@for($i=0; $i < 8; $i++)
			<td style="background-color: #c8d8e9;
		font-weight: bold; text-align: right;"></td>
		@endfor
	</tr>
	<tr>
		<td></td>
		<td style="background-color: #e7eff9;
		font-weight: bold; ">Pending</td>
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['pending'][$m]}}</td>
		   @else
		   		<td style="background-color: #e7eff9; font-weight: bold; text-align: right;">{{$data['forRender']['pending'][$m]}}</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['forRender']['pending'][$m]}}</td>
		<td></td>
		@for($i=0; $i < 8; $i++)
			<td style="background-color: #e7eff9;
		font-weight: bold; text-align: right;"></td>
		@endfor
	</tr>
	<tr>
		<td></td>
		<td style="background-color: #c8d8e9;
		font-weight: bold; text-align: left;">{{$data['pYear']}}</td>
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRevenuePYear'][$m]}}</td>
		   @else
		   		<td style="background-color: #f2f2f2; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRevenuePYear'][$m]}}</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['forRender']['executiveRevenuePYear'][$m]}}</td>
		<td></td>
		@for($i=0; $i < 8; $i++)
			<td style="background-color: #c8d8e9;
		font-weight: bold; text-align: right;"></td>
		@endfor
	</tr>
	<tr>
		<td></td>
		<td style="background-color: #e7eff9;
		font-weight: bold; ">Var RF vs Target</td>
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{$data['forRender']['RFvsTarget'][$m]}}</td>
		   @else
		   		<td style="background-color: #e7eff9; font-weight: bold; text-align: right;">{{$data['forRender']['RFvsTarget'][$m]}}</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['forRender']['RFvsTarget'][$m]}}</td>
		<td></td>
		@for($i=0; $i < 8; $i++)
			<td style="background-color: #e7eff9;
		font-weight: bold; text-align: right;"></td>
		@endfor
	</tr>

	<tr>
		<td></td>		
		<td style="background-color: #c8d8e9;
		font-weight: bold; ">% Target Achievement</td>
		@for($m=0; $m < sizeof ($data['month']); $m++)
		   @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
		   		<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],0)}} %</td>
		   @else
		   		<td style="background-color: #f2f2f2; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],0)}} %</td>
		   @endif
		@endfor
		<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],0)}} %</td>
		<td></td>
		@for($i=0; $i < 8; $i++)
			<td style="background-color: #c8d8e9;
		font-weight: bold; text-align: right;"></td>
		@endfor
	</tr>
</table>

@for($c = 0; $c < sizeof ($data['forRender']['client']); $c++)
<table>
	<tr>
		<td></td>
		<td style=" background-color: #0f243e; color: #ffffff; font-weight: bold; text-align: left;">{{$data['forRender']['client'][$c]['clientName']}} - {{$data['forRender']['client'][$c]['agencyName']}} </td>
		@for($m=0; $m < sizeof($data['month']); $m++)
			@if($m == 3 || $m == 7 || $m == 11 || $m == 15)
				<td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: right;">{{$data['month'][$m]}}</td>
			@else
				<td style="background-color: #4f81bd; color: #ffffff; font-weight: bold; text-align: right;">{{$data['month'][$m]}}</td>
			@endif
		@endfor
		<td style=" background-color: #0f243e; color: #ffffff; font-weight: bold; text-align: right;">Total</td>
		<td></td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Closed</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Cons. (%)</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Exp</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Prop</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Adv</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Contr</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Total</td>
        <td style="background-color: #a6a6a6; font-weight: bold; text-align: right;">Lost</td>
	</tr>
	<tr>
		<td rowspan="6" style="text-align:center; background-color: #0070c0; font-weight: bold; color: #ffffff;">DISC</td>
	</tr>
	<tr>
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;"> Rolling Fcast {{$data['cYear']}}</td>
        @for($m=0; $m < sizeof ($data['month']) ; $m++)
	        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
	            <td style="background-color:#c3d8ef; text-align:right; font-weight: bold;">{{$data['forRender']['lastRollingFCSTDisc'][$c][$m]}}</td>
	        @else
	            <td  style="background-color: #dbe5f0; text-align:right; font-weight: bold;">{{$data['forRender']['lastRollingFCSTDisc'][$c][$m]}}</td>                    
	        @endif
	    @endfor
	    <td style="background-color: #143052; text-align:right; font-weight: bold; color: #ffffff;">
	    	{{$data['forRender']['lastRollingFCSTDisc'][$c][$m]}}
	    </td>		
	    <td></td>			
	    @if ($data['forRender']['fcstAmountByStageDisc'][$c])             	
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][4]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{number_format(number_format($data['forRender']['fcstAmountByStageDisc'][$c][1][7],0),0)}} %</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][0]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][1]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][2]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][3]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][6]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageDisc'][$c][1][5]}} </td>                        
	    @else
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00%</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>    
	    @endif
    </tr>
    <tr>
        <td style="background-color:#c9d8e8; text-align:left; font-weight: bold;">Manual Estimation</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #ffffff; text-align:right;">{{$data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
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
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;">Booking</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; text-align: right; font-weight: bold;">{{$data['forRender']['clientRevenueCYearDisc'][$c][$m]}}</td>
            @else
                <td style="background-color: #dbe5f0; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenueCYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; text-align: right; font-weight: bold; color: #ffffff;">{{$data['forRender']['clientRevenueCYearDisc'][$c][$m]}}</td>
        <td></td>
        @for ($i=0; $i < 8; $i++) 
        	<td style="background-color: #c8d8e9; font-weight: bold; text-align: right;"></td>
        @endfor
    </tr>
    <tr>
        <td style="background-color:#c9d8e8; text-align:left; font-weight: bold;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #ffffff; text-align:right;">{{$data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
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
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;">Var RF vs {{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; text-align: right; font-weight: bold;">{{$data['forRender']['rollingFCSTDisc'][$c][$m] - $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @else
                <td style="background-color: #dbe5f0; font-weight: bold; text-align:right;">{{$data['forRender']['rollingFCSTDisc'][$c][$m] - $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; text-align: right; font-weight: bold; color: #ffffff;">{{$data['forRender']['rollingFCSTDisc'][$c][$m] - $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
        <td></td>
        @for ($i=0; $i < 8; $i++) 
        	<td style="background-color: #c8d8e9; font-weight: bold; text-align: right;"></td>
        @endfor
    </tr>
    <tr>
		<td rowspan="6" style="text-align:center; background-color: #003c66; font-weight: bold; color: #ffffff;"> SONY </td>
	</tr>    
    <tr>
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;"> Rolling Fcast {{$data['cYear']}}</td>
        @for($m=0; $m < sizeof ($data['month']) ; $m++)
	        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
	            <td style="background-color:#c3d8ef; text-align:right; font-weight: bold;">{{$data['forRender']['lastRollingFCSTSony'][$c][$m]}}</td>
	        @else
	            <td  style="background-color: #dbe5f0; text-align:right; font-weight: bold;">{{$data['forRender']['lastRollingFCSTSony'][$c][$m]}}</td>                    
	        @endif
	    @endfor
	    <td style="background-color: #143052; text-align:right; font-weight: bold; color: #ffffff;">
	    	{{$data['forRender']['lastRollingFCSTSony'][$c][$m]}}
	    </td>		
	    <td></td>			
	    @if ($data['forRender']['fcstAmountByStageSony'][$c])             	
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][4]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{number_format($data['forRender']['fcstAmountByStageSony'][$c][1][7],0)}} %</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][0]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][1]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][2]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][3]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][6]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][5]}} </td>                        
	    @else
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00%</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>    
	    @endif
    </tr>
    <tr>
        <td style="background-color:#c9d8e8; text-align:left; font-weight: bold;">Manual Estimation</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #ffffff; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
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
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;">Booking</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; text-align: right; font-weight: bold;">{{$data['forRender']['clientRevenueCYearSony'][$c][$m]}}</td>
            @else
                <td style="background-color: #dbe5f0; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenueCYearSony'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; text-align: right; font-weight: bold; color: #ffffff;">{{$data['forRender']['clientRevenueCYearSony'][$c][$m]}}</td>
        <td></td>
        @for ($i=0; $i < 8; $i++) 
        	<td style="background-color: #c8d8e9; font-weight: bold; text-align: right;"></td>
        @endfor
    </tr>
    <tr>
        <td style="background-color:#c9d8e8; text-align:left; font-weight: bold;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #ffffff; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
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
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;">Var RF vs {{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; text-align: right; font-weight: bold;">{{$data['forRender']['rollingFCSTSony'][$c][$m] - $data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
            @else
                <td style="background-color: #dbe5f0; font-weight: bold; text-align:right;">{{$data['forRender']['rollingFCSTSony'][$c][$m] - $data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; text-align: right; font-weight: bold; color: #ffffff;">{{$data['forRender']['rollingFCSTSony'][$c][$m] - $data['forRender']['clientRevenuePYearSony'][$c][$m]}}</td>
        <td></td>
        @for ($i=0; $i < 8; $i++) 
        	<td style="background-color: #c8d8e9; font-weight: bold; text-align: right;"></td>
        @endfor
    </tr>
    <tr>
		<td rowspan="6" style="text-align:center; background-color: #0f243e; font-weight: bold; color: #ffffff;"> TT </td>
	</tr>    
    <tr>
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;"> Rolling Fcast {{$data['cYear']}}</td>
        @for($m=0; $m < sizeof ($data['month']) ; $m++)
	        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
	            <td style="background-color:#c3d8ef; text-align:right; font-weight: bold;">{{$data['forRender']['lastRollingFCSTSony'][$c][$m] + $data['forRender']['lastRollingFCSTDisc'][$c][$m]}}</td>
	        @else
	            <td  style="background-color: #dbe5f0; text-align:right; font-weight: bold;">{{$data['forRender']['lastRollingFCSTSony'][$c][$m] + $data['forRender']['lastRollingFCSTDisc'][$c][$m]}}</td>                    
	        @endif
	    @endfor
	    <td style="background-color: #143052; text-align:right; font-weight: bold; color: #ffffff;">
	    	{{$data['forRender']['lastRollingFCSTSony'][$c][$m] + $data['forRender']['lastRollingFCSTDisc'][$c][$m]}}
	    </td>		
	    <td></td>			
	    @if ($data['forRender']['fcstAmountByStageSony'][$c] || $data['forRender']['fcstAmountByStageDisc'][$c])             	
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][4] + $data['forRender']['fcstAmountByStageDisc'][$c][1][4]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{number_format($data['forRender']['fcstAmountByStageSony'][$c][1][7] + $data['forRender']['fcstAmountByStageDisc'][$c][1][7],0)}} %</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][0] + $data['forRender']['fcstAmountByStageDisc'][$c][1][0]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][1] + $data['forRender']['fcstAmountByStageDisc'][$c][1][1]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][2] + $data['forRender']['fcstAmountByStageDisc'][$c][1][2]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][3] + $data['forRender']['fcstAmountByStageDisc'][$c][1][3]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][6] + $data['forRender']['fcstAmountByStageDisc'][$c][1][6]}} </td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">{{$data['forRender']['fcstAmountByStageSony'][$c][1][5] + $data['forRender']['fcstAmountByStageDisc'][$c][1][5]}} </td>                        
	    @else
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00%</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>
	        <td style="background-color: #c8d8e9; font-weight: bold; text-align:right;">0.00</td>    
	    @endif
    </tr>
    <tr>
        <td style="background-color:#c9d8e8; text-align:left; font-weight: bold;">Manual Estimation</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m] + $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m] + $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #ffffff; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m] + $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
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
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;">Booking</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; text-align: right; font-weight: bold;">{{$data['forRender']['clientRevenueCYearSony'][$c][$m] + $data['forRender']['clientRevenueCYearDisc'][$c][$m]}}</td>
            @else
                <td style="background-color: #dbe5f0; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenueCYearSony'][$c][$m] + $data['forRender']['clientRevenueCYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; text-align: right; font-weight: bold; color: #ffffff;">{{$data['forRender']['clientRevenueCYearSony'][$c][$m] + $data['forRender']['clientRevenueCYearDisc'][$c][$m]}}</td>
        <td></td>
        @for ($i=0; $i < 8; $i++) 
        	<td style="background-color: #c8d8e9; font-weight: bold; text-align: right"></td>
        @endfor
    </tr>
    <tr>
        <td style="background-color:#c9d8e8; text-align:left; font-weight: bold;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style=" background-color: #c3d8ef; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m] + $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @else
                <td style=" background-color: #e6e6e6; font-weight: bold; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m] + $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
            @endif
        @endfor
        <td style=" background-color: #143052; font-weight: bold; color: #ffffff; text-align:right;">{{$data['forRender']['clientRevenuePYearSony'][$c][$m] + $data['forRender']['clientRevenuePYearDisc'][$c][$m]}}</td>
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
        <td style="background-color:#dbe5f0; text-align:left; font-weight: bold;">Var RF vs {{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; text-align: right; font-weight: bold;">{{($data['forRender']['rollingFCSTSony'][$c][$m] - $data['forRender']['clientRevenuePYearSony'][$c][$m]) + ($data['forRender']['rollingFCSTDisc'][$c][$m] - $data['forRender']['clientRevenuePYearDisc'][$c][$m])}}</td>
            @else
                <td style="background-color: #dbe5f0; font-weight: bold; text-align:right;">{{($data['forRender']['rollingFCSTSony'][$c][$m] - $data['forRender']['clientRevenuePYearSony'][$c][$m]) + ($data['forRender']['rollingFCSTDisc'][$c][$m] - $data['forRender']['clientRevenuePYearDisc'][$c][$m])}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; text-align: right; font-weight: bold; color: #ffffff;">{{($data['forRender']['rollingFCSTSony'][$c][$m] - $data['forRender']['clientRevenuePYearSony'][$c][$m]) + ($data['forRender']['rollingFCSTDisc'][$c][$m] - $data['forRender']['clientRevenuePYearDisc'][$c][$m])}}</td>
        <td></td>
        @for ($i=0; $i < 8; $i++) 
        	<td style="background-color: #c8d8e9; font-weight: bold; text-align: right;"></td>
        @endfor
    </tr>
</table>
@endfor