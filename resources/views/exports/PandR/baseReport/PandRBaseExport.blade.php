<table>
	<tr><th style="background-color: #0070c0; color: #FFFFFF; font-weight: bold; " colspan="18"> {{$data['baseReportView']}} - {{$data['forRender']['currencyName']}}/{{$data['forRender']['valueView']}}</th></tr>
</table>

<table>
    <tr>
        <td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> DN </td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style= "background-color: #4f81bd; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @else
                <td style= "background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;" >Target</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['targetValuesTT'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{number_format($data['forRender']['targetValuesTT'][$m],2,',','.')}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['targetValuesTT'][$m],2,',','.')}}</td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Rolling Fcast {{$data['cYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
               <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['rollingFCSTTT'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{number_format($data['forRender']['rollingFCSTTT'][$m],2,',','.')}}</td>
            @endif
        @endfor               
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['rollingFCSTTT'][$m],2,',','.')}}</td>

    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;">Bookings</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['bookingsTT'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{number_format($data['forRender']['bookingsTT'][$m],2,',','.')}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['bookingsTT'][$m],2,',','.')}}</td>  
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Pending</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
               <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['pendingTT'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{number_format($data['forRender']['pendingTT'][$m],2,',','.')}}</td>
            @endif
        @endfor               
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['pendingTT'][$m],2,',','.')}}</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold; text-align: left;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['lastYearTT'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{number_format($data['forRender']['lastYearTT'][$m],2,',','.')}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['lastYearTT'][$m],2,',','.')}}</td>  
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Var RF vs Target</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
               <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['rfVsTargetTT'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{number_format($data['forRender']['rfVsTargetTT'][$m],2,',','.')}}</td>
            @endif
        @endfor               
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['rfVsTargetTT'][$m],2,',','.')}}</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;">% Target Achievement</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 )
                <td style="background-color: #c3d8ef; font-weight: bold; ">{{number_format($data['forRender']['targetAchievement'][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{number_format($data['forRender']['targetAchievement'][$m],2,',','.')}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['targetAchievement'][$m],2,',','.')}}</td>  
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
                <td style= "background-color: #4f81bd; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @else
                <td style= "background-color: #143052; font-weight: bold; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
        <td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td> 
    </tr>
    @if($data['baseReport'] == 'brand' || $data['baseReport'] == 'ae'){
        <tr>
            <td style="background-color: #e7eff9; font-weight: bold;"> Target </td>
            @for ($m=0; $m < sizeof($data['month']) ; $m++) 
                @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                    <td style="background-color: #c3d8ef; font-weight: #c3d8ef; font-weight: bold;">{{number_format($data['forRender']['targetValues'][$c][$m],2,',','.')}}</td>
                @else
                    <td style="background-color: #e7eff9; font-weight: bold;">{{number_format($data['forRender']['targetValues'][$c][$m],2,',','.')}}</td>
                @endif
            @endfor
            <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['targetValues'][$c][$m],2,',','.')}}</td>
        </tr>
    @endif
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold;"> Rolling Fcast {{$data['cYear']}} </td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: bold;">{{number_format($data['forRender']['rollingFCST'][$c][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{number_format($data['forRender']['rollingFCST'][$c][$m],2,',','.')}}</td>        
            @endif
        @endfor
        echo "<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['rollingFCST'][$c][$m],2,',','.')}}</td>
    </tr>
    
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Booking</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: #c3d8ef; font-weight: bold;">{{number_format($data['forRender']['bookings'][$c][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{number_format($data['forRender']['bookings'][$c][$m],2,',','.')}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['bookings'][$c][$m],2,',','.')}}</td>
    </tr>
    <tr>
        <td style="background-color: #dce6f1; font-weight: bold; text-align: left;">{{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: bold;">{{number_format($data['forRender']['lastYear'][$c][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #dce6f1; font-weight: bold;">{{number_format($data['forRender']['lastYear'][$c][$m],2,',','.')}}</td>        
            @endif
        @endfor
        echo "<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['lastYear'][$c][$m],2,',','.')}}</td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; font-weight: bold;">Var RF vs {{$data['pYear']}}</td>
        @for ($m=0; $m < sizeof($data['month']) ; $m++) 
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style="background-color: #c3d8ef; font-weight: #c3d8ef; font-weight: bold;">{{number_format($data['forRender']['rfVsCurrent'][$c][$m],2,',','.')}}</td>
            @else
                <td style="background-color: #e7eff9; font-weight: bold;">{{number_format($data['forRender']['rfVsCurrent'][$c][$m],2,',','.')}}</td>
            @endif
        @endfor
        <td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{number_format($data['forRender']['rfVsCurrent'][$c][$m],2,',','.')}}</td>
</tr>
</table>

@endfor