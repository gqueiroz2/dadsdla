<table>
	<tr>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Updated date</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">{{$data['updateInfo'][0]['updateDate']}}</td>
	</tr>
	<tr>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Sales Rep</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">{{$data['updateInfo'][0]['salesRep']}}</td>
	</tr>
	<tr>
		<th colspan='10' style="font-weight: bold; background-color: #0047b3; color: white; text-align: center;"> Control Panel - {{$data['agencyGroupName']}}</th>
	</tr>
	<tr>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Client</td>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Agency</td>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">{{$data['year']-2}}</td>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">{{$data['year']-1}}</td>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">{{$data['year']}}</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">Forecast {{$data['year']}}</td>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Total {{$data['year']}}</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">Forecast SPT {{$data['year']}}</td>
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Percentage</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">Status</td>
	</tr>
	@for($b = 0; $b < sizeof($data['bvTest']) ; $b++)
		<tr>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['client']}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['agency']}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b][$data['year']-2]}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b][$data['year']-1]}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b][$data['year']]}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['prev']}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['prevActualSum']}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['sptPrev']}}</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['variation']}}%</td>
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['bvTest'][$b]['status']}}</td>
		</tr>
	@endfor
	<tr>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">TOTAL</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;"></td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total'][$data['year']-2]}}</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total'][$data['year']-1]}}</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total'][$data['year']]}}</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total']['prev']}}</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total']['prevActualSum']}}</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total']['sptPrev']}}</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;">{{$data['total']['variation']}}%</td>
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;"></td>
	</tr>		
</table>