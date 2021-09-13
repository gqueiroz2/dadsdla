<table>
	<tr><th style="background-color: #0070c0; color: #FFFFFF; font-weight: bold; " colspan="18"> {{$data['baseReportView']}} - {{$data['forRender']['currencyName']}}/{{$data['forRender']['valueView']}}</th></tr>
</table>

<table>
    <tr>
        <td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> DN </td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style= "background-color: #4f81bd; font-weight: bold; color: #FFFFFF; text-align: right;">{{$data['month'][$m]}}</td>
            @else
                <td style= "background-color: #143052; font-weight: bold; color: #FFFFFF; text-align: right;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; text-align: right;">Total</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;" >Target</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{$data['forRender']['targetValuesTT'][$m]}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{$data['forRender']['targetValuesTT'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['targetValuesTT'][$m]}}</td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Rolling Fcast {{$data['cYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
               <td style="background-color: #c3d8ef; font-weight: bold; ">{{$data['forRender']['rollingFCSTTT'][$m]}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{$data['forRender']['rollingFCSTTT'][$m]}}</td>
            @endif
        @endfor               
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['rollingFCSTTT'][$m]}}</td>

    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;">Bookings</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{$data['forRender']['bookingsTT'][$m]}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{$data['forRender']['bookingsTT'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['bookingsTT'][$m]}}</td>  
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Pending</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
               <td style="background-color: #c3d8ef; font-weight: bold; ">{{$data['forRender']['pendingTT'][$m]}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{$data['forRender']['pendingTT'][$m]}}</td>
            @endif
        @endfor               
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['pendingTT'][$m]}}</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold; text-align: left;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{$data['forRender']['lastYearTT'][$m]}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{$data['forRender']['lastYearTT'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['lastYearTT'][$m]}}</td>  
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Var RF vs Target</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
               <td style="background-color: #c3d8ef; font-weight: bold; ">{{$data['forRender']['rfVsTargetTT'][$m]}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{$data['forRender']['rfVsTargetTT'][$m]}}</td>
            @endif
        @endfor               
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['rfVsTargetTT'][$m]}}</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;">% Target Achievement</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],0)}}%</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],0)}}%</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold; text-align: right;">{{number_format($data['forRender']['targetAchievement'][$m],0)}}%</td>  
    </tr>           
</table>

@for($c=0; $c < sizeof($data['forRender']['list']); $c++)

<table>
    <tr>                     
    	@if($data['baseReport'] == 'brand')
    		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['list'][$c]['brand']}}</td>
    	@elseif($data['baseReport'] == 'ae')
    		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['list'][$c]['salesRep']}}</td>
    	@elseif($data['baseReport'] == 'client')
    		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['list'][$c]['clientName']}}</td>
    	@elseif($data['baseReport'] == 'agency')
    		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['list'][$c]['agencyName']}}</td>
    	@else
    		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['list'][$c]['agencyGroup']}}</td>
    	@endif
    	@for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style= "background-color: #4f81bd; font-weight: bold; color: #FFFFFF; text-align: right;">{{$data['month'][$m]}}</td>
            @else
                <td style= "background-color: #143052; font-weight: bold; color: #FFFFFF; text-align: right;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; text-align: right;">Total</td> 
    </tr>
    @if($data['baseReport'] == 'brand' || $data['baseReport'] == 'ae'){
        <tr>
            <td style="background-color: #e7eff9; font-weight: bold;"> Target </td>
            @for ($m=0; $m < sizeof($data['month']) ; $m++) 
                @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                    <td style="background-color: #c3d8ef; font-weight: #c3d8ef; font-weight: bold;">{{$data['forRender']['targetValues'][$c][$m]}}</td>
                @else
                    <td style="background-color: #e7eff9; font-weight: bold;">{{$data['forRender']['targetValues'][$c][$m]}}</td>
                @endif
            @endfor
            <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['targetValues'][$c][$m]}}</td>
        </tr>
    @endif
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;"> Rolling Fcast {{$data['cYear']}} </td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['rollingFCST'][$c][$m]}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{$data['forRender']['rollingFCST'][$c][$m]}}</td>        
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['rollingFCST'][$c][$m]}}</td>
    </tr>
    
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Booking</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: #c3d8ef; font-weight: bold;">{{$data['forRender']['bookings'][$c][$m]}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{$data['forRender']['bookings'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['bookings'][$c][$m]}}</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold; text-align: left;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: bold;">{{$data['forRender']['lastYear'][$c][$m]}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{$data['forRender']['lastYear'][$c][$m]}}</td>        
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['lastYear'][$c][$m]}}</td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Var RF vs {{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: #c3d8ef; font-weight: bold;">{{$data['forRender']['rfVsCurrent'][$c][$m]}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{$data['forRender']['rfVsCurrent'][$c][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['forRender']['rfVsCurrent'][$c][$m]}}</td>
</tr>
</table>

@endfor