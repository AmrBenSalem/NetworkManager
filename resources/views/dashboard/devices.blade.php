@extends('layouts.dashboard')

@section('content')

<div class="row">
	<div class="padding-top">
		<div class="col-xs-offset-2 col-xs-10 col-md-offset-1 col-md-10">
			<div class="row">
				<h3 style="margin-bottom: 20px;"><b> Devices : </b></h3>	
				<div class="well well-margin-top">

					<div class="form-group pull-right">
					    <input type="text" class="search form-control" placeholder="What you looking for?">
					</div>
					<span class="counter pull-right"></span>
					<table class="table table-hover table-condensed results" id="device-table">
							<thead>
								<tr>
									<th>
										Hostname
									</th>
									<th>
										Ip address
									</th>
									<th>
										Network
									</th>
									<th>
										Reference
									</th>

									<th>
										Software
									</th>
									<th>
										Version
									</th>
									<th class="center-td">
										Status
									</th>
									<th>
									</th>
								</tr>
							</thead>
					    <tr class="warning no-result">
					      <td colspan="4"><i class="fa fa-warning"></i> No result</td>
					    </tr>
					  </thead>
					  <tbody>
					  <form method="POST" action="/dashboard/device/getconfig">
					  	{{csrf_field()}}
							@foreach ($devices as $device)
								<tr class="device-table">
									<td>
										{{$device->sysname}}
									</td>
									<td>
										{{$device->dip}}					
									</td>
									<td>
										{{$device->nlabel}} ({{$device->nname}})
									</td>
									<td>
										{{$device->sysref}}
									</td>
									<td>
										{{$device->syssoftware}}
									</td>
									<td>
										{{$device->sysversion}}
									</td>
									<td class="center-td">
										<div class="updown label label-{{$device->status == "c" ? "success" : 'danger'}}">  {{$device->status == "c" ? "UP" : 'DOWN'}}</div>
									</td>
									<td>
									<button type='submit' name='read' id='read' class='button-custom-toolbar' value='{{ $device->dip }}' data-toggle="tooltip"  data-container="body" data-placement="bottom" title="View configuration"><img class='custom-toolbar-item' src='/images/read-icon.png' > </button>
									</td>
								</tr>
							@endforeach
						</form>
					  </tbody>
					</table>
{{-- 					<div class="table-responsive">
						<table class="table table-condensed" id="device-table">
							<thead>
								<tr>
									<th>
										Hostname
									</th>
									<th>
										Ip address
									</th>
									<th>
										Network
									</th>
									<th>
										Reference
									</th>

									<th>
										Software
									</th>
									<th>
										Version
									</th>
									<th class="center-td">
										Status
									</th>
								</tr>
							</thead>
							@foreach ($devices as $device)
								<tr class="device-table">
									<td>
										{{$device->sysname}}
									</td>
									<td>
										{{$device->dip}}					
									</td>
									<td>
										{{$device->nlabel}} ({{$device->nname}})
									</td>
									<td>
										{{$device->sysref}}
									</td>
									<td>
										{{$device->syssoftware}}
									</td>
									<td>
										{{$device->sysversion}}
									</td>
									<td class="center-td {{$device->status == "c" ? "success" : 'danger'}}">
										{{$device->status == "c" ? "UP" : 'DOWN'}}
									</td>
								</tr>
							@endforeach
						</table>
					</div> --}}


				</div>
			</div>
			
			<div class="row" id="getinter">
				
			</div>

		</div>
	</div>
</div>





@endsection