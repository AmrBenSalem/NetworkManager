@extends('layouts.dashboard')

@section('content')


<div class="row">
	<div class="col-xs-offset-2 col-xs-10 col-lg-offset-1 col-lg-11">
		<div class="padding-top">
			<h3><b>Overview : </b></h3>
			<br>
			<div class="col-xs-5 col-lg-4 " id="chart-1">No data available</div>
			<div class="col-xs-5 col-lg-4" id="chart-2">No data available</div>
			<div style="margin-left: 12px;" class="col-xs-2 col-lg-3" id="chart-3">No data available</div>

		</div>
	</div>
</div>

			
		
			    @if ($data) 
			    {{$columnChart = new App\FusionCharts("doughnut2d", "doughnut1", 420, 420, "chart-1", "json", $data)}}
                {{$columnChart->render()}}
                @endif
                @if ($data2)
			    {{$columnChart = new App\FusionCharts("doughnut2d", "doughnut2", 420, 420, "chart-2", "json", $data2)}}
                {{$columnChart->render()}}
                @endif
                @if ($data3)
			    {{$columnChart = new App\FusionCharts("bar2d", "bar2d1", 300, 250, "chart-3", "json", $data3)}}
                {{$columnChart->render()}}
				@endif

@endsection
