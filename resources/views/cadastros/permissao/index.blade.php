
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Permissões de Acesso
	        <small>
	            
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('permissao/create') }}"><i class="fa fa-plus-square"></i> Nova Permissão</a>

	  </div>

	</div>

</div>

<div class="clearfix"></div>

<div class="row">

@include('flash::message')

<div class="col-md-12 col-sm-12 col-xs-12">
  
	<div class="x_panel">

	    <div class="x_title">
	      
			<h2>Permissões</h2>

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
            <th class="column-title">Nome Permissão </th>
            <th class="column-title">Descrição </th>
            <th class="column-title">Cadastro em </th>
            <th class="column-title no-link last"><span class="nobr">Ação</span>
            </th>
            <th class="bulk-actions" colspan="7">
              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
            </th>
          </tr>
        </thead>

        <tbody>
          
          @if (count($permissoes) > 0)
          	@foreach ($permissoes as $permissao)
	          <tr class="even pointer">
	            <td class="a-center ">
	              <input type="checkbox" class="flat" name="table_records">
	            </td>
	            <td class=" ">{{ $permissao->id }} </td>
	            <td class=" ">{{ $permissao->display_name }} </td>
	            <td class=" ">{{ $permissao->description }} </i></td>
	            <td class=" ">{{ convertDatePt(explode(" ", $permissao->created_at)[0]) }}</td>
	            <td class=" last"><a href="#">View</a>
	            </td>
	          </tr>
          	 @endforeach
          @endif

        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

@endsection