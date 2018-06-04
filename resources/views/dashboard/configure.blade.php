@extends('layouts.dashboard')

@section('content')

<div class="row">
	<div class="padding-top">
	<div class="col-md-offset-1 col-xs-offset-2 col-md-11">
			
				<form class="form-horizontal" role="form" method="POST" action="{{ url('/dashboard/configure')}}">
        {{ csrf_field() }}
        <div id="first-panel" class="panel panel-primary margin-top-panel col-md-3">
        <div class="panel-body">	
              
  					<p style=" margin-bottom: 37px; "> Please select the ip address of the device(s) you want to configure </p> 

               <div class="row">
               <button type="button" class="width-btn center-block btn btn-primary" data-toggle="modal" data-target="#configModal">Select devices</button>
               </div>
               <div class="row">
                  <h4 id="or" class="text-center">--- OR ---</h4>
               </div>
               <div class="row">
               <button  type="button" class="width-btn center-block btn btn-primary" data-toggle="modal" data-target="#scanModal">Scan now</button>
               </div>
               
               <br>
                @if(session()->has('anotherscan'))
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    The scheduled scan is running , please wait till it ends.
                </div>
                @endif
				</div>
        </div>



        <div class="panel panel-primary margin-top-panel col-md-8">
        <div class="panel-body">  
        
              
            <p> Please select the template you want to use </p> 
            <div style="margin-left: 20px;" class="form-group form-inline">
            <label for="select_template" class="control-label">Template :</label>
             
              <select class="form-control" name="select_template" id="select_template" >
              <option disabled selected>Select a template</option>
                
                @if ($templates)
                @foreach ($templates as $template )
                <option value="{{ $template->title }}" >{{$template->title}}</option>
                @endforeach
                @endif

                @if ($templates2)
                @foreach ($templates2 as $template )
                <option value="{{ $template->title }}" >{{$template->title}}</option>
                @endforeach
                @endif

              </select>
            </div>
               {{--  <button type="submit" class="btn btn-info">button</button> --}}
                @if (session('successconfigure'))
                          <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Success !</strong> Configured with success.
                        </div>
                @endif
 
          <div class="col-md-7">
              <div id="config">

                      @if(session('failconf'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          {!! session('failconf') !!}
                        </div>
                      @endif
              </div>
          </div>
        </div>
        </div>



      <div id="configModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Devices<h4>
      </div>
      <div style="margin-right: 10px;" class="modal-body">
        <p>Please select the devices you want to configure : </p>
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
                  </th>
                </tr>
              </thead>
              <tr class="warning no-result">
                <td colspan="4"><i class="fa fa-warning"></i> No result</td>
              </tr>
            </thead>
            <tbody>
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
                    <input type="checkbox" name="ch[]" value=" {{ $device->dip }}">
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Submit</button>
      </div>
    </div>

  </div>
</div>



        <button type="submit" class="col-md-offset-6 col-md-1 btn btn-success">Confirm</button>


       </form>
			
	</div>
	</div>


				

<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                  <h4>Processing...</h4>
                    <img src="/images/loading.gif" class="icon" />
                    
                </div>
            </div>
        </div>
    </div>
</div>	



<div id="scanModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Scan Now</h4>
      </div>
      <div class="modal-body">
        <p>Please select the networks you want to scan : </p>
                  <table class="table">
                       <thead>
                        <tr>
                            <th>
                            Name
                            </th>
                            <th>
                            Network IP
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        @if (session()->has('networks'))
                        @foreach (session()->get('networks') as $network)
                            <tr>
                                <td class="col-sm-2">{{$network->nname}}</td>
                                <td class="col-sm-3">{{$network->ip}}</td>
                                <td class="col-sm-2 form-inline checknet">
                                      <input value="{{$network->ip}}" type="checkbox" class="checknet" name="checknet">
                                </td>
                        </tr>
                        @endforeach
                        
                        @else
                          No network available.
                        @endif
                  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"  id="scannow" data-toggle="modal" data-target= "#processing-modal" >Submit</button>
      </div>
    </div>

  </div>
</div>








</div>





@endsection