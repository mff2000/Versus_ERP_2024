
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
            Projetos
            <small>
                Cadastro de Projetos
            </small>
        </h3>
    </div>

    <div class="title_right">
        
      <div class="pull-right">

        <a class="btn btn-sm btn-primary" href="{{ url('projeto/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

      </div>

    </div>

</div>
   
<div class="clearfix"></div>

<div class="row">

    @include('flash::message')

    <div class="col-md-12 col-sm-12 col-xs-12">
      
        <div class="x_panel">

            <div class="x_title">
              
                <h2>Projetos</h2>

                <ul class="nav navbar-right panel_toolbox">
                    
                </ul>

                <div class="clearfix"></div>

            </div>

            <div class="x_content" id="centro-menu">

                
                <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    
                    <thead>
                      <tr class="headings">
                        <th style="width:50px">
                          <input type="checkbox" id="check-all" class="flat">
                        </th>
                        <th class="column-title" style="width:150px">ID </th>
                        <th class="column-title" style="width:150px">Código </th>
                        <th class="column-title">Descrição </th>
                        <th class="column-title no-link last">
                            <span class="nobr">Ação</span>
                        </th>
                      </tr>
                    </thead>

                    <tbody>
                        
                        @if (count($projetos) > 0)
                            @foreach ($projetos as $projeto)
                                @if(!$projeto->conta_superior)
                                <tr class="even pointer">
                                    
                                    <td class="a-center ">
                                      <input type="checkbox" class="flat" name="table_records">
                                    </td>
                                    <td>{{ $projeto->id }}</td>
                                    <td>{{ $projeto->codigo }}</td>
                                    <td>
                                        
                                        <ul class="nav">
                                            <li>
                                                <a>{{ $projeto->descricao }} <span class="fa fa-chevron-down"></span></a>
                                                
                                                @if (count($projeto->children) > 0)
                                                <ul class="nav child_menu" style="display: none">

                                                    <?php 
                                                        $childrens = $projeto->children;
                                                        $ids = array();
                                                    ?>
                                                        
                                                    <?php $cont = 0 ?>
                                                    <?php 
                                                        $child = $childrens[$cont];
                                                        $codPai = $projeto->codigo.".";
                                                    ?>
                                                    
                                                    @while( $child )
                                                        

                                                        @if($child->deleted_at == null)
                                                                   
                                                            <li class="child">

                                                            <span>
                                                                
                                                                <a href="#">{{ $codPai.$child->codigo }} - {{ $child->descricao }}</a>
                                                                
                                                                <a href="{{ url('projeto').'/'.$child->id.'/create' }}"><i class="fa fa-plus"></i></a>
                                                                <a href="{{ url('projeto').'/'.$child->id.'/edit' }}"><i class="fa fa-pencil"></i></a>
                                                                
                                                                <form action="{{ url('projeto').'/' .$child->id }}" method="POST">
                                                                    {{ csrf_field() }}
                                                                    {{ method_field('DELETE') }}
                                                                    <i type="button" onclick="$('#modal-{{$child->id}}').modal('toggle')" style="margin-bottom:0;">
                                                                        <i class="fa fa-trash"></i>
                                                                    </i>

                                                                    <div id="modal-{{ $child->id }}" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                                        <div class="modal-dialog modal-sm">
                                                                            <div class="modal-content">

                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                    <h4 class="modal-title" id="myModalLabel2">Confirmação</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <h4>Deseja realmento apagar este registro?</h4>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                                                                                    <button type="subtmit" class="btn btn-danger">Apagar</button>
                                                                                </div>
                                                                            
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                
                                                            </span>
                                                        
                                                        @endif

                                                        <?php

                                                            array_push($ids, $child->id);

                                                            if( count($child->children) > 0 )  {

                                                                $codPai .= $child->codigo.".";

                                                                $child = $child->children[0];

                                                                echo '<ul class="nav child_menu" style="display: none">';

                                                            } else {

                                                                echo '</li>';

                                                                procura:
                                                                if(isset($child->parent)) {
                                                                    
                                                                    $parent = $child->parent;
                                                                    
                                                                    //
                                                                    if (count($parent->children) > 0) {
                                                                        $newChild = null;
                                                                        foreach($parent->children as $item) {
                                                                            if(!in_array($item->id, $ids)) {
                                                                                $newChild = $item;
                                                                                //
                                                                                break;
                                                                            }
                                                                        }
                                                                    } 
                                                                } else {
                                                                    $cont++;
                                                                    if(isset($childrens[$cont])) {
                                                                        $child = $childrens[$cont];
                                                                        if($child->deleted_at == null) {
                                                                            $codPai = substr( $codPai, 0, strrpos($codPai, "."));
                                                                        }
                                                                    }
                                                                    else
                                                                        break;
                                                                }

                                                                if($newChild != null) {
                                                                    $child=$newChild;
                                                                }
                                                                else {
                                                                    $child = $parent;
                                                                    if($child->deleted_at == null) {
                                                                        $codPai = substr( $codPai, 0, strrpos($codPai, "."));
                                                                        $codPai = substr( $codPai, 0, strrpos($codPai, ".")).".";
                                                                    }
                                                                    echo '</ul>';
                                                                    goto procura;
                                                                }


                                                            }
                                                        ?>
                                                        
                                                    @endwhile

                                                </ul>
                                                @endif
                                            </li>
                                        </ul>
                                        
                                    </td>
                                    
                                    <td class=" last" style="width:190px">
                                       <ul>                                            
                                            <li style="float:left;">
                                                <button onclick="window.location='{{ url('projeto').'/'.$projeto->id.'/create' }}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Adicionar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-plus"></i> </button>
                                            </li>
                                            {!! linksDefault('projeto', $projeto->id) !!}
                                        </ul>
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

</div><!-- /#row -->

<script type="text/javascript">


// Sidebar
$(function () {
    $CENTRO_MENU = $('#centro-menu');
    //$SIDEBAR_MENU.find('li ul').slideUp();
    $CENTRO_MENU.find('li').removeClass('active');

    $CENTRO_MENU.find('li').on('click', function(ev) {
        var link = $('a', this).attr('href');

        // prevent event bubbling on parent menu
        if (link) {
            ev.stopPropagation();
        } 
        // execute slidedown if parent menu
        else {
            if ($(this).is('.active')) {
                $(this).removeClass('active');
                $('ul', this).slideUp(function() {
                    //setContentHeight();
                });
            } else {
                $CENTRO_MENU.find('li').removeClass('active');
                $CENTRO_MENU.find('li ul').slideUp();
                
                $(this).addClass('active');
                $('ul', this).slideDown(function() {
                    //setContentHeight();
                });
            }
        }
    });

    
});

</script>

@endsection