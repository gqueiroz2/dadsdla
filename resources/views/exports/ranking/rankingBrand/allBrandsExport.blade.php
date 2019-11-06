<table>
    <tr>
        <th colspan="9">
            <span>
                <b>
                    {{$data['region']}} - Brand Ranking (BKGS) : ({{$data['currency'][0]['name']}}/{{strtoupper($data['value'])}})
                </b>
            </span>
        </th>
    </tr>

    @for($m = 0; $m < sizeof($data['mtx'][0]); $m++)
        <tr>
            @for($n = 0; $n < sizeof($data['mtx']); $n++)
                @if($m == 0)
                    <td>{{$data['mtx'][$n][$m]}}</td>
                @else
                    @if(is_numeric($data['mtx'][$n][$m]))
                        @if($n >= 4 && $n <= 7)
                            <td>{{number_format($data['mtx'][$n][$m],0,',','.')}} %</td>
                        @else
                            <td>{{number_format($data['mtx'][$n][$m],0,',','.')}}</td>  
                        @endif
                    @else
                        <td>{{$data['mtx'][$n][$m]}}</td>
                    @endif
                @endif
            @endfor
    @endfor

    </tr>

</table>