<table>
	<tr>	
		<th style="background-color: #0047b3; text-align: center;" colspan='16'> Brazil - Viewer WBD {{$data['year']}} - ({{$data['currencies']}}) </th>
	</tr>
	<tr>
		<td style="background-color: #0f243e; text-align: center; color: white;">Total</td>
		<td style="background-color: #0f243e; text-align: center; " colspan='13'></td>
		<td style="background-color: #0f243e; text-align: center; color: white;" >{{$data['total']['sumGrossRevenue']}}</td>
		<td style="background-color: #0f243e; text-align: center; color: white;" >{{$data['total']['sumNetRevenue']}}</td>
	</tr>
	<tr>
		<td style="background-color: #e6e6e6; text-align: center;">Company</td>
		<td style="background-color: #e6e6e6; text-align: center;">Year</td>
		<td style="background-color: #e6e6e6; text-align: center;">Month</td>
		<td style="background-color: #e6e6e6; text-align: center;">Previous AE</td>
		<td style="background-color: #e6e6e6; text-align: center;">Client</td>
		<td style="background-color: #e6e6e6; text-align: center;">Agency</td>
		<td style="background-color: #e6e6e6; text-align: center;">Platform</td>
		<td style="background-color: #e6e6e6; text-align: center;">Brand</td>
		<td style="background-color: #e6e6e6; text-align: center;">Feed Code</td>
		<td style="background-color: #e6e6e6; text-align: center;">Order</td>
		<td style="background-color: #e6e6e6; text-align: center;">Pi Number</td>
		<td style="background-color: #e6e6e6; text-align: center;">Property</td>
		<td style="background-color: #e6e6e6; text-align: center;">Director</td>
		<td style="background-color: #e6e6e6; text-align: center;">Current AE</td>
		<td style="background-color: #e6e6e6; text-align: center;">Gross Revenue</td>
		<td style="background-color: #e6e6e6; text-align: center;">Net Revenue</td>					
	</tr>
	@for ($m=0; $m <sizeof($data['mtx']) ; $m++) 
		<tr> 
			<td style='text-align: center;'>{{$data['mtx'][$m]['company']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['year']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['month']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['oldRep']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['client']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['agency']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['feedType']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['brand']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['feedCode']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['internalCode']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['piNumber']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['property']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['manager']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['salesRep']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['grossRevenue']}}</td>
			<td style='text-align: center;'>{{$data['mtx'][$m]['netRevenue']}}</td>		
		</tr>
	@endfor		
</table>