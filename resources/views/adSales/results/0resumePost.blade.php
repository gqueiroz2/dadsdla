@extends('layouts.mirror')
@section('title', 'Resume Results')
@section('head')	
	<script src="/js/resultsResume.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsResumePost') }}">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft"><span class="bold"> Region: </span></label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID )}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							{{$render->value()}}
						</div>
						<div class="col">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">		
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
		<div class="row mt-2	">
			<div class="col">				
				<table class="table table-bordered" style="width: 100%;">
					<tr>
						<th class="darkBlue center" colspan="11"><span style="font-size:18px;"> Resume ({{$currencyS}}/{{$valueS}}) - {{$cYear}} </span> </th>
					</tr>
					<tr>
						<th class="darkBlue"> Month </th>
						<th class="lightBlue"> {{$salesShow}} </th>
						<th class="lightBlue"> Actual </th>
						<th class="darkBlue"> Target </th>
						<th class="darkBlue"> Corporate </th>
						<!--
						<th class="darkBlue"> P&R FCST </th>
						<th class="darkBlue"> Finance FCST </th>
						-->
						<th class="darkBlue"> {{$pYear}} </th>
						<th class="grey"> {{$salesShow}}/Target </th>
						<th class="grey"> {{$salesShow}}/Corporate </th>
						{{--
						<th class="grey"> Sales/P&R </th>
						<th class="grey"> Sales/Finance </th>
						--}}
						<th class="grey"> {{$salesShow}}/{{$pYear}} </th>
					</tr>
					@for($m = 0;$m < sizeof($matrix);$m++)
						@if($matrix[$m]['month'] == "Total")
							<?php $bck = "darkBlue";?>
						@else
							@if($m%2 == 0) <?php $bck = 'odd'; ?> @else <?php $bck = 'even'; ?> @endif
						@endif
							<tr>
								<td class="{{$bck}}">  {{ $matrix[$m]['month'] }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['sales']) }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['actual']) }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['target']) }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['corporate']) }} </td>
								{{--
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['pAndR']) }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['finance']) }} </td>
								--}}
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['pYear']) }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverTarget']) }}% </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverCorporate']) }}% </td>
								{{--
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverPAndR']) }} </td>
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverFinance']) }} </td>
								--}}
								<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesYoY']) }}% </td>
							</tr>
						
					@endfor
				</table>


			</div>
		</div>
	</div>
@endsection