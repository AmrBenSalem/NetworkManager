<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Network Manager</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/bootstrap-notifications.css" rel="stylesheet">
    <script src="/js/jquery-3.1.1.js"></script>
    <script type="text/javascript" src="/js/fusioncharts/fusioncharts.js"></script>
    <script type="text/javascript" src="/js/fusioncharts/themes/fusioncharts.theme.fint.js"></script>
    <script src="/js/app.js"></script>
    <script src="/js/scripts.js"></script>


    

    
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <!-- <div id="app"> -->
        
            <div class="container-fluid">
                <div class="row">
                <nav id ="nave" class="navbar navbar-default navbar-static-top navbar-fixed-top nav-margin">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Network Manager
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (session()->has('pings'))
                        <li class="padding-top-text">
                            <p><b>Reachability : </b></p>
                        </li>
                        <li id="ping-table" class="li-height status-table">
                            <table class="table table-bordered">
                                
                                    <tr>
                                        <td id="all-ping">{{session()->get('pings')[0]+session()->get('pings')[1]}}</td>
                                        <td data-content="{{ session()->has('online') ? session()->get('online') : 'No device Reachable' }}" rel="popover" data-placement="bottom" data-original-title="Reachable" data-trigger="hover" data-html="true" id="success-ping"  class="success">{{session()->get('pings')[0]}}</td>
                                        <td data-content="{{ session()->has('offline') ? session()->get('offline') : 'No device Unreachable' }}" rel="popover" data-placement="bottom" data-original-title="Unreachable" data-trigger="hover" data-html="true" id="failed-ping" class="danger">{{session()->get('pings')[1]}}</td>
                                    </tr>
                            </table>

                        </li>
                        @endif

          <li class="dropdown padding-notifications">
            <a id="dLabel" role="button" data-toggle="dropdown">
                    <img id="notifications-icon" src="/images/notifications-icon.png">
                        <i  data-count="" id="notification-number"></i>
                       
            </a>
              
              <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">
                
                <div class="notification-heading"><h4 class="menu-title">Notifications</h4>
                </div>
                <li class="divider no-margin-hr" style="
    margin-top: 0px;
    margin-bottom: 0px;
"></li>
               <div id="notification-content" class="notifications-wrapper">
                    @if (session()->has('notifications'))
                        {!! session()->get('notifications') !!}
                    @else
                        There is no notifications
                    @endif
               </div>
                <li class="divider"></li>
                <div class="notification-footer text-right"><a href="{{url('/dashboard/logs')}}"<h4 class="menu-title">View all</h4></a></div>
              </ul>
          </li>
                        <li id="setting-li"><a id="openmodal" class="navright" data-toggle="modal" data-target="#myModal"><img id="setting-icon" src="/images/settings-icon.png"></a></li>
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            {{-- <li><a href="{{ route('register') }}">Register</a></li> --}}
                        @else
                            <li class="dropdown">
                                <a class="navright" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
            </div>
           
        <div class="row">
            <div class="col-sm-1 custom-nav no-left">
               <!-- <div class="col-sm-8 no-left"> -->
                    <ul class="list-group">
                        <li>
                            <a href="{{ url('/dashboard') }}" class="navlink" data-toggle="tooltip" title="Home" data-container="body"  data-placement="right"> <img class="navimage list-group-item" src="/images/home-icon.png"> </a>
                        </li>
                        @if (Auth::user()->role != "Supervisor")
                        <li>
                            <a href="{{ url('/dashboard/configure') }}" class="navlink" data-toggle="tooltip"  data-container="body" data-placement="right" title="Configure"> <img class="navimage list-group-item" src="/images/configure-icon.png"> </a>
                        </li>
                        <li>
                            <a href="{{ url('/dashboard/template') }}" class="navlink" data-toggle="tooltip"  data-container="body" data-placement="right" title="Templates"> <img class="navimage list-group-item" src="/images/templates-icon.png"> </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ url('/dashboard/devices') }}" class="navlink" data-toggle="tooltip"  data-container="body" data-placement="right" title="Devices"> <img class="navimage list-group-item" src="/images/device-icon2.png"> </a>
                        </li>
                        @if (Auth::user()->role != "Supervisor")
                        <li>
                            <a href="{{ url('/dashboard/backup') }}" class="navlink" data-toggle="tooltip"  data-container="body" data-placement="right" title="Backup"> <img class="navimage list-group-item" src="/images/backup-icon.png"> </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ url('/dashboard/logs') }}" class="navlink" data-toggle="tooltip"  data-container="body" data-placement="right" title="Logs"> <img class="navimage list-group-item" src="/images/logs-icon.png"> </a>
                        </li>
                    </ul>
                <!-- </div> -->
            </div>
        </div>

        
    
        @yield('content')
        
    </div>



<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">


        <button id="padding-x" type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="padding-title" class="modal-title">Configuration</h4>

  <div class="modal-body">
        <ul class="nav nav-tabs" id="tabContent">
                <li><a href="#account" data-toggle="tab">Account</a></li>
                <li><a href="#networks" data-toggle="tab">Networks</a></li>
                <li><a href="#tftp" data-toggle="tab">Tftp Server</a></li>
                <li><a href="#scheduling" data-toggle="tab">Scheduling</a></li>
                @if (Auth::user()->role == "Superadmin")
                    <li><a href="#users" data-toggle="tab">Users</a></li>
                @endif
        </ul>
          
      <div class="tab-content">
            
            <div class="tab-pane active padding-content" id="account">
            @if (session()->has('user'))
                <form role="form" action="{{url('/dashboard/user/modify')}}" method="POST">
                    {{ csrf_field() }}

                    
                    <input class="form-control" type="hidden" name="id" value="{{session()->get('user')->id}}">
                    <label for="name" class="control-label">Name</label>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <input class="form-control" type="text" name="name" value="{{session()->get('user')->name}}" required>
                    </div>
                    <label for="email" class="control-label">E-Mail Address</label>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input class="form-control" type="email" name="email" value="{{session()->get('user')->email}}" required>
                    </div>
                    <button class="col-md-offset-10 btn btn-success">Edit</button>
                </form>
            @else 
                Error in session
            @endif                
                
            </div>
            
            <div class="tab-pane padding-content" id="networks">
                <form class="form-horizontal" method="POST" role="form" action="{{ url('/dashboard/network/add')}}">
                    {{ csrf_field() }}
                  <div class="form-group">
                    {{-- <label for="ip" class="col-sm-3 control-label">Network IP</label> --}}
                    <div class="col-sm-3 no-padding-right {{ $errors->has('nname') ? ' has-error' : '' }}">
                      <input type="text" class="form-control" name="nname" id="name" placeholder="Network name" required>
                    </div>
                    <div class="col-sm-4 {{ $errors->has('ip') ? ' has-error' : '' }}">
                      <input type="text" class="form-control" name="ip" id="ip" placeholder="Network IP Exp : 192.168.1.0/24" pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}/(?:\d|[12]\d|3[01])$" required>
                    </div>
                    <div class="col-sm-3" {{ $errors->has('profile') ? ' has-error' : '' }}>
                        <select class="form-control" name="profile" id="profile" required="">
                            @if(session()->has('profiles'))
                                @foreach (session()->get('profiles') as $profile)
                                    <option value="{{$profile->label}}"  {{ session()->get('active_profile')->label == $profile->label ? 'selected' : '' }} >{{$profile->label}}</option>
                                @endforeach
                            @else
                                    <option value="" selected disabled="">No profile</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-sm-2">
                      <button type="submit" class="btn btn-success">Add</button>
                    </div>
                  </div>
                  </form>
                   


                  <table class="table table-hover">
                       <thead>
                        <tr>
                            <th>
                            Name
                            </th>
                            <th>
                            Network IP
                            </th>
                            <th>
                            Status
                            </th>
                        </tr>
                        </thead>
                        @if (session()->has('networks'))
                        @foreach (session()->get('networks') as $network)
                            <tr>
                                <td class="col-sm-2">{{$network->nname}}</td>
                                <td class="col-sm-3">{{$network->ip}}</td>
                                <td class="col-sm-2 form-inline"><div id="networkstatus">{{$network->status}}</div>
                                    @if ($network->status =="enabled")
                                        <input type="checkbox" class="check" name="check" checked>
                                    @else
                                        <input type="checkbox" class="check" name="check">
                                    @endif
                                </td>
                                <td class="col-sm-3 selectpaddingtop">
                                    <select  class="form-control profileselect" name="profileselect">
                                    @if(session()->has('profiles'))
                                        @foreach (session()->get('profiles') as $profile)
                                            <option value="{{$network->ip}}"  {{ $network->profile == $profile->label ? 'selected' : '' }} >{{$profile->label}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </td>
                                <td class="col-md-2 no-padding">
                                <div class="custom-toolbar2">                             
                                <form class="form-display-inline" method="POST" action="{{ url('/dashboard/network/delete') }}"> 
                                    {{ csrf_field() }}
                                    <button  type="submit" name="deletenet" class="button-custom-toolbar" value="{{ $network->ip }}">
                                     <img class="custom-toolbar-item"" src="/images/delete-icon.png"> 
                                    </button>
                                </form>

{{--                                 <button name="edit" id="edit" class="edit button-custom-toolbar" value="">
                                    <img class="custom-toolbar-item"" src="/images/edit-icon.png"> 
                                </button> --}}
                                </div>
                                </td>

                            
                        </tr>
                        @endforeach
                        @endif
                  </table>
                
            </div> 


            <div class="tab-pane padding-content" id="tftp">
                <form role="form" action="{{url('/dashboard/setting/tftp')}}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                    <label for="name" class="control-label">Tftp server IP : </label>
                    <div >
                      <input type="text" value="{{ session()->has('tftp') ? session()->get('tftp') : '' }}" class="form-control" name="tftp" id="tftp" placeholder="Tftp server ip address" pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}" required>
                    </div>
                    </div>
                    <button class="col-md-offset-10 btn btn-success">Submit</button>
                </form>               
                
            </div>

            <div class="tab-pane padding-content" id="scheduling">
                <form class="form-horizontal" role="form" action="{{url('/dashboard/setting/manageprofile')}}" method="POST">
                    {{ csrf_field() }}
                        
                        <div class="row">
                        <label for="profiles" class="control-label col-xs-3">Select profile : </label>
                        <div class="col-xs-4">
                            <select class="form-control" name="profiles" id="profiles">
                                    @if(session()->has('profiles') && session()->has('active_profile'))
                                        @foreach (session()->get('profiles') as $profile)
                                            <option value="{{$profile->label}}"  {{ session()->get('active_profile')->label == $profile->label ? 'selected' : '' }} >{{$profile->label}}</option>
                                        @endforeach
                                    @endif
                            </select>
                        </div>

                        <button class="btn btn-danger" name="delete" id="delete" value="delete">Delete</button>
                        </div>
                    </form>
                        <hr>
                        <form id="sched" class="form-horizontal" role="form" action="{{url('/dashboard/setting/addprofile')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                        <label for="label" class="control-label col-xs-3">Label : </label>
                        <div class="col-xs-4{{ $errors->has('label') ? ' has-error' : '' }}"> 
                             <input class="form-control" type="text" name="label" id="label" value="{{ session()->has('active_profile') ? session()->get('active_profile')->label : '' }}" required>
                        </div>
                        </div>
                        <br>

                        <div class="row">
                        <label for="pingnombre" class="control-label col-xs-3">Ping check : </label>
                        <div class="control-label col-xs-1">
                            Every
                        </div>
                        <div class="col-xs-3"> 
                             <input class="form-control" type="number" name="pingnombre" id="pingnombre" value="{{ session()->has('active_profile') ? session()->get('active_profile')->pingnombre : '' }}" required min="1" max="60">
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control" name="pingtime" id="pingtime">
                                    @if (session()->has('active_profile'))
                                        <option value="minute" {{ session()->get('active_profile')->pingtime == "minute" ? 'selected' : '' }}>Minute(s)</option>
                                        <option value="hour" {{ session()->get('active_profile')->pingtime == "hour" ? 'selected' : '' }}>Hour(s)</option>
                                        <option value="day" {{ session()->get('active_profile')->pingtime == "Day" ? 'selected' : '' }}>Day(s)</option>
                                    @else
                                        <option value="minute">Minute(s)</option>
                                        <option value="hour">Hour(s)</option>
                                        <option value="day">Day(s)</option>
                                    @endif
                            </select>

                        </div>
                        </div>
                        <br>
                        <div class="row">
                        <label for="scannombre" class="control-label col-xs-3">Discovery scan : </label>
                        <div class="control-label col-xs-1">
                            Every
                        </div>
                        <div class="col-xs-3"> 
                             <input class="form-control" id="scannombre" type="number" name="scannombre" value="{{ session()->has('active_profile') ? session()->get('active_profile')->scannombre : '' }}" required min="1" max="60">
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control" name="scantime" id="scantime">
                                    @if (session()->has('active_profile'))
                                        <option value="minute" {{ session()->get('active_profile')->scantime == "minute" ? 'selected' : '' }}>Minute(s)</option>
                                        <option value="hour" {{ session()->get('active_profile')->scantime == "hour" ? 'selected' : '' }}>Hour(s)</option>
                                        <option value="day" {{ session()->get('active_profile')->scantime == "Day" ? 'selected' : '' }}>Day(s)</option>
                                    @else
                                        <option value="minute">Minute(s)</option>
                                        <option value="hour">Hour(s)</option>
                                        <option value="day">Day(s)</option>
                                    @endif
                            </select>
                        </div>
                        </div>
                        <br>
                        <div class="row">
                        <label for="backupnombre" class="control-label col-xs-3">Backup : </label>
                        <div class="control-label col-xs-1">
                            Every
                        </div>
                        <div class="col-xs-3"> 
                             <input class="form-control" id="backupnombre" type="number" name="backupnombre" value="{{ session()->has('active_profile') ? session()->get('active_profile')->backupnombre : '' }}" required min="1" max="60">
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control" name="backuptime" id="backuptime">
                                    @if (session()->has('active_profile'))
                                        <option value="minute" {{ session()->get('active_profile')->backuptime == "minute" ? 'selected' : '' }}>Minute(s)</option>
                                        <option value="hour" {{ session()->get('active_profile')->backuptime == "hour" ? 'selected' : '' }}>Hour(s)</option>
                                        <option value="day" {{ session()->get('active_profile')->backuptime == "Day" ? 'selected' : '' }}>Day(s)</option>
                                    @else
                                        <option value="minute">Minute(s)</option>
                                        <option value="hour">Hour(s)</option>
                                        <option value="day">Day(s)</option>
                                    @endif

                            </select>
                        </div>
                        </div>
                                                            
                        <br>
                        <div class="col-xs-offset-8">
                        <button type="reset" class="btn btn-default">Reset</button>
                        <button class=" btn btn-success" name="confirm" id="confirm">Add/Modify</button>
                        </div>
                </form>
           </div> 

        @if (Auth::user()->role == "Superadmin")
           <div class="tab-pane padding-content" id="users">
                    <div class="row">

       <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">Users list</div>
                <div class="panel-body">
                    <table class="table table-hover">
                    @if (session()->has('users'))
                        @foreach (session()->get('users') as $user)
                        <tr> 
                            <td class="usertable">{{ $user->name }}</td>
                            <td class="custom-toolbar">                             
                                <form class="form-display-inline" method="POST" action="{{ url('/dashboard/user/delete') }}"> 
                                    {{ csrf_field() }}
                                    <button  type="submit" name="delete" id="delete" class="button-custom-toolbar" value="{{ $user->id }}">
                                     <img class="custom-toolbar-item"" src="/images/delete-icon.png"> 
                                    </button>
                                </form>

                            </td>
                            
                        </tr>
                        @endforeach
                    @else
                    <h4>No user available</h4>
                    @endif

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-primary">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/dashboard/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name2" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                            <label for="role" class="col-md-4 control-label">Permissions</label>

                            <div class="col-md-6">
                              <select id="role" class="form-control" name="role" required>
                                <option id="default" disabled selected>Select a permission</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Admin">Admin</option>
                                <option value="Superadmin">Super Admin</option>
                              </select>
                                @if ($errors->has('role'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button id="resetuser" type="reset" class="btn btn-default">
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
           </div>
           @endif
        </div>

    </div>
    </div>
    </div>
    </div>






@if ($errors->has('name') || $errors->has('email') || $errors->has('ip'))
  
{{--   <script>
$(document).ready(function() {
        alert('a');
        $('#myModal').modal('toggle');
        // $('#openmodal').click(); 
    }
    
  </script>
 --}}
 @endif


</body>
</html>
