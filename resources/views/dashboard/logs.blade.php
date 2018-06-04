@extends('layouts.dashboard')

@section('content')


<div class="row">
	<div class="col-xs-offset-2 col-xs-10 col-md-offset-1 col-md-11">
		<div class="padding-top">
					
			<div class="col-xs-5">
			<h3><b>Syslog history : </b></h3>
			@if ($syslogs)
			<table class="results" class="logs">
				@foreach ($syslogs as $syslog)
					<tbody>
					<tr>
					<td>
						<blockquote class="sizeblock" style="border-left-color: #aecada;">
			  				<p>{{$syslog->formatline}}</p>
			  				<footer>{{$syslog->agentip}}({{$syslog->hostname}}) in {{$syslog->enterprise}} at <cite title="Source Title">{{$syslog->traptime}}</cite></footer>
						</blockquote>
					</td>
					</tr>
					</tbody>
				@endforeach
			</table>
			@endif
			</div>

			<div class="col-xs-offset-1 col-xs-4">
			<h3><b>Informs history : </b></h3>
			@if ($informs)
			<table class="results" class="logs">
				@foreach ($informs as $informs)
					<tbody>
					<tr>
					<td>
						<blockquote class="sizeblock" style="border-left-color: #aecada;">
			  				<p>{{$informs->formatline}}</p>
			  				<footer>{{$informs->agentip}}({{$informs->hostname}}) in {{$informs->enterprise}} at <cite title="Source Title">{{$informs->traptime}}</cite></footer>
						</blockquote>
					</td>
					</tr>
					</tbody>
				@endforeach
			</table>
			@endif
			</div>

			<div class="col-xs-2">
			<div class="form-group pull-right " style="margin-top: 20px;">
				<input type="text" class="search form-control" placeholder="What you looking for?">
			</div>
			<span class="counter pull-right"></span>
			</div>

		</div>
	</div>
</div>


@endsection
