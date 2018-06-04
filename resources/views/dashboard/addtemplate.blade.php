@extends('layouts.dashboard')

@section('content')

<div class="row">


	<div class="col-md-offset-1 col-xs-offset-2">
		<div class="padding-top-temp">
		<div class="col-md-4">

			<div class="panel panel-primary ">
				<div class="panel-heading"  >
					<span style="color : white;">Templates : </span>
				</div>
				<div class="panel-body">

					<table class="table table-hover">
						@foreach ($templates as $template)
						<tr> 
							<td class="templatetable">{{ $template->title }}</td>
							<td class="custom-toolbar">                             
								<form class="form-display-inline" method="POST" action="{{ url('/dashboard/managetemplate/delete') }}">	
									{{ csrf_field() }}
									<button  type="submit" name="delete" id="delete" class="button-custom-toolbar" value="{{ $template->title }}">
									 <img class="custom-toolbar-item"" src="/images/delete-icon.png"> 
									</button>
								</form>

								<button name="edit" id="edit" class="edit button-custom-toolbar" value="{{-- {{ $template->title }} --}}">
									<img class="custom-toolbar-item"" src="/images/edit-icon.png"> 
								</button>
							</td>
							
						</tr>
						@endforeach

					</table>
					
				</div>

			</div>
		</div>

		<div class="col-md-7">

		<div class="row">
				<form class="form-horizontal" role="form" method="POST" action=" {{ url('/dashboard/template')}} ">
					{{ csrf_field() }}
						

						<div class="panel panel-primary">
							<div class="panel-heading"  >
								<span style="color : white;">Details : </span>
							</div>
							<div class="panel-body" style="padding-right: 30px; padding-left: 30px;">
							
								<div class="form-group{{ $errors->has('title') ? ' has-error' :'' }}">
									<label for="title">Title</label>
									<input id="title" type="text" name="title" class="form-control" value="" required>

									@if ($errors->has('title'))
										<span class="help-block">
											<strong> {{ $errors->first('title') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('commands') ? ' has-error' : '' }}">
									<label for='commands'>Commands to execute</label>
									<textarea  class="form-control" rows="5" id="commands" name="commands" value="">
									</textarea>

									@if ($errors->has('commands'))
										<span class="help-block">
											<strong> {{ $errors->first('commands') }}</strong>
										</span>
									@endif
								</div>
							</div>


								<div class="form-group">
									<div class="col-md-offset-9 col-md-3">
									<button id="add" name="add" type="reset" class="btn btn-default">Reset</button>
		                                <button id="save" name="save" type="submit" class="btn btn-success">
		                                    Save
		                                </button>
		                            </div>
								</div>

								</div>



				</form>

			</div>

		</div>

		</div>



			<div class="col-md-10">
			@if (session('success'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Success !</strong> Template added with success.
				</div>
			@endif
			@if (session('successdelete'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Success !</strong> Template deleted with success.
				</div>
			@endif
			@if (session('successedit'))
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Success !</strong> Template edited with success.
				</div>
			@endif
			</div>

	</div>



</div>


@endsection