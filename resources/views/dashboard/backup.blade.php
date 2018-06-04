@extends('layouts.dashboard')

@section('content')

<div class="row">
	<div class="padding-top">
	<div class="col-xs-offset-2 col-xs-10 col-md-offset-1 col-md-11">
  <br>

          <div id="second-panel" class="panel panel-primary col-md-3" style="margin-top: 23px;">
        <div class="panel-body">  
              
            <p style=" margin-bottom: 37px; "> Click on "Backup Now" to do an instant backup on online devices. </p> 

               <div class="row">
{{--                     <form role="form" action="{{url('/dashboard/backup/back')}}" method="POST">
                      {{ csrf_field()}} --}}
                      <button id='backupall' style="margin-bottom: 10px;" class="width-btn center-block btn btn-primary" data-toggle="modal" data-target="#processing-modal">BACKUP NOW</button>
{{--                     </form>
 --}}               </div>



        </div>
        </div>


    @if (isset($networks))


		<ul id="tree1">
    <div class="col-md-8 relative">
    <form class="form-display-inline" method="POST" action="{{ url('/dashboard/backup/manage') }}"> 
      {{ csrf_field() }}
           <br /> 
       		@foreach ($networks as $network)
          <div class="panel panel-primary" style="margin-bottom: 10px;">
          <div class="panel-body">
                <li><a href="#">{{$network}}</a>
                <ul>
                @foreach ($backups as $backup)
                @if ($network == $backup->nname)
					<li>{{$backup->ip}}  {{$backup->created_at}}   
          <button  type='submit' name='restore' id='restore' class='button-custom-toolbar' value='{{ $backup->ip }}' data-toggle="tooltip"  data-container="body" data-placement="bottom" title="Restore"><img class='custom-toolbar-item' src='/images/restore-icon.png'  > </button>  
          <button  type='submit' name='backup' id='backup' class='button-custom-toolbar' value='{{ $backup->ip }}' data-toggle="tooltip"  data-container="body" data-placement="bottom" title="Backup"><img class='custom-toolbar-item' src='/images/backup-icon2.png' > </button>  
          <button type='submit' name='read' id='read' class='button-custom-toolbar' value='{{ $backup->ip }}' data-toggle="tooltip"  data-container="body" data-placement="bottom" title="View configuration"><img class='custom-toolbar-item' src='/images/read-icon.png' > </button>
          </li>
              @endif
				@endforeach
				</ul>
				</li>
        </div>
        </div>
			@endforeach
				
        

      </form>
      </div>
      </ul>
  @endif

  <div class="col-md-2 col-md-offset-2">

  </div>

<div id="msgs" class="col-md-6">

          @if (session('successrestore'))
           <div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Success !</strong> Restored with success.
            </div>
          @endif
</div>


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

</div>
    {{-- <script src="/js/bootstrap-treeview.js"></script> --}}
    <script src="/js/tree.js"></script>
    <script type="text/javascript">

    </script>


@endsection