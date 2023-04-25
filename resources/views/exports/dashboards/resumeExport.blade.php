<div style="display: inline-flex;">
<table>
	<tr>
		<th colspan='11' style="font-weight: bold; background-color: #0047b3; color: white; text-align: center;">{{$data['agencyGroupName']}}</th>
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
		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">Sales Rep</td>
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
			@for($u = 0; $u < sizeof($data['updateInfo']) ; $u++)
			<td style="font-weight: bold; background-color: {{$data['color'][$b]}}}; text-align: center;">{{$data['updateInfo'][$u]['salesRep']}}</td>
			@endfor
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
		<td style="font-weight: bold; background-color: #143052; color: white; text-align: center;"></td>
	</tr>		
</table>

<table>
	<tr>
		<td colspan='11' style="background-color: #143052; color: white; text-align: center;"> Historical Investment </td>
	</tr>
</table>

<table>							
	<tr>
		<th colspan='8' style='background-color: #0047b3; font-weight: bold; text-align: center; color: white;'> {{$data['year']-1}}</th>
	</tr>
	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">CLIENT</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">AGENCY</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">GE</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">SPORTS</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">NEWS</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">DIGITAL</td>
		<td style="background-color: #143052; color: white; text-align: center;">TOTAL</td>
		<td style="background-color: black; color: white; text-align: center;">SPT</td>								
	</tr>
	@for($h = 0; $h < sizeof($data['historyPyear']) ; $h++)	
	<tr>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPyear'][$h]['clientName']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPyear'][$h]['agencyName']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPyear'][$h]['geCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPyear'][$h]['sportsCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPyear'][$h]['newsCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPyear'][$h]['digitalCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalByClientPyear'][$h]/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center; font-weight: bold;">{{$data['historyPyear'][$h]['sptCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
	</tr>
	@endfor
	<tr>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">TOTAL</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;"></td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPyear']['geCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPyear']['sportsCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPyear']['newsCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPyear']['digitalCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPyear']['totalCluster']/$data['pRateWM']}}</td>
		<td style="background-color: black; color: white; text-align: center;">{{$data['totalClusterPyear']['sptCluster']/$data['pRateWM']}}</td>
	</tr>
</table>

<table>							
	<tr>
		<th colspan='8' style='background-color: #0047b3; font-weight: bold; text-align: center; color: white;'> {{$data['year']-2}}</th>
	</tr>
	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">CLIENT</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">AGENCY</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">GE</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">SPORTS</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">NEWS</td>
		<td style="background-color: #c3d8ef; font-weight: bold; text-align: center;">DIGITAL</td>
		<td style="background-color: #143052; color: white; text-align: center;">TOTAL</td>
		<td style="background-color: black; color: white; text-align: center;">SPT</td>								
	</tr>
	@for($h1 = 0; $h1 < sizeof($data['historyPpyear']); $h1++)	
	<tr class="even center" style="font-size:16px; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;">
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['clientName']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['agencyName']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['geCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['sportsCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['newsCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['digitalCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalByClientPpyear'][$h1]/$data['pRateWM']}}</td>
		<td style="background-color: #f9fbfd; font-weight: bold; text-align: center;">{{$data['historyPpyear'][$h1]['sptCluster'][0]['netRevenue']/$data['pRateWM']}}</td>
	</tr>
	@endfor
	<tr>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">TOTAL</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;"></td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPpyear']['geCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPpyear']['sportsCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPpyear']['newsCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPpyear']['digitalCluster']/$data['pRateWM']}}</td>
		<td style="background-color: #143052; color: white; font-weight: bold; text-align: center;">{{$data['totalClusterPpyear']['totalCluster']/$data['pRateWM']}}</td>
		<td style="background-color: black; color: white; text-align: center;">{{$data['totalClusterPpyear']['sptCluster']/$data['pRateWM']}}</td>
	</tr>
</table>  


</div>
