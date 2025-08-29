
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Usuários
	        <small>
	            
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('user/create') }}"><i class="fa fa-plus-square"></i> Novo Usuário</a>

	  </div>

	</div>

</div>

<div class="clearfix"></div>

<div class="row">

@include('flash::message')

<div class="col-md-12 col-sm-12 col-xs-12">
  
	<div class="x_panel">

	    <div class="x_title">
	      
			<h2>Usuários</h2>

			<ul class="nav navbar-right panel_toolbox">
				
				<li>
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				</li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="#">Settings 1</a>
				    </li>
				    <li><a href="#">Settings 2</a>
				    </li>
				  </ul>
				</li>
				<li><a class="close-link"><i class="fa fa-close"></i></a>
				</li>
			</ul>

			<div class="clearfix"></div>

	    </div>

    <div class="x_content">

      <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th>
              <input type="checkbox" id="check-all" class="flat">
            </th>
            <th class="column-title">ID </th>
            <th class="column-title">Nome </th>
            <th class="column-title">E-mail </th>
            <th class="column-title">Perfil </th>
            <th class="column-title">Cadastro em </th>
            <th class="column-title">Status </th>
            <th class="column-title no-link last"><span class="nobr">Ação</span>
            </th>
            <th class="bulk-actions" colspan="7">
              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
            </th>
          </tr>
        </thead>

        <tbody>
          
          @if (count($users) > 0)
          	@foreach ($users as $user)
          		@if($user->roles[0]->name != 'superadmin')
					<tr class="even pointer">
						<td class="a-center ">
						  <input type="checkbox" class="flat" name="table_records">
						</td>
						<td class=" ">{{ $user->id }} </td>
						<td class=" ">{{ $user->name }} </td>
						<td class=" ">{{ $user->email }} </i></td>
						<td class=" ">
							@if(count($user->roles)>0)
							{{ $user->roles[0]->display_name }} 
							@endif
						</td>
						<td class=" "></td>
						<td class=" "> Ativo </td>
						<td class=" last"><a href="#">View</a>
						</td>
					</tr>
				@endif
          	 @endforeach
          @endif

        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

@endsection