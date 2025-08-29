
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
          {{ (isset($tabela->id)) ? 'Editar' : 'Incluir' }} Tabela de Preço <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right">
          
      </div>

    </div>
</div>


<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">

        @include('flash::message')
       
        <div class="x_panel">

            

              <div class="x_content">
                  <form id="bordera-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('tabelaPreco') }}" novalidate>
                  {{ csrf_field() }}

                    <input id="id" type="hidden" name="id" value="{{ isset($tabela) ? $tabela->id : "" }}">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados da Tabela de Preço</h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">


                      <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                          <label for="descricao" class="control-label">Descrição:*</label>
                          <input id="descricao" class="form-control col-sm-2" name="descricao" value="{{ isset($tabela) ? $tabela->descricao : old('descricao') }}" type="text" required max-length="45" />
                          @if ($errors->has('descricao'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('descricao') }}</strong>
                              </span>
                            @endif
                      </div>

                    </div>

                    @if(isset($tabela->produtos))

                    <div class="form-group">
                        
                        <div class="x_panel">
                          
                          <div class="x_title">
                            <h2>Lista de Produtos<small></small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>

                          <div class="x_content collaped" style="display:none">

                            <table class="table table-striped">
                            <thead>
                              <tr class="headings">
                                <th class="column-title">ID </th>
                                <th class="column-title">Descrição </th>
                                <th class="column-title">Unidade</th>
                                <th class="column-title">Grupo</th>
                                <th class="column-title">Preço </th>
                                <th class="column-title no-link last" width="45">
                                  <span class="nobr">Ação</span>
                                </th>
                              </tr>
                            </thead>

                            <tbody>
                                
                                @foreach ($tabela->produtos as $produto)

                                  <tr class="even pointer">
                                    <td class=" ">{{ $produto->id }} </td>
                                    <td class=" ">{{ $produto->descricao }} </i></td>
                                    <td class=" ">{{ $produto->unidade->descricao }}</i></td>
                                    <td class=" ">{{ $produto->grupo->descricao }}</i></td>
                                    <td class="" width="20%">
                                      <input id="tabela_produto_{{$produto->id}}" class="form-control currency" name="produto_id[{{$produto->id}}]" value="{{ $produto->pivot->preco }} "  type="text" />
                                    </td>
                                    <td class=" last">
                                      <ul>
                                        <li style="float:left;">
                                          <button onclick="window.location='{{url('tabelaPreco/excluiProduto/'.$tabela->id.'/'.$produto->id)}}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Apagar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-trash"></i> </button>
                                        </li>
                                      </ul>
                                    </td>
                                  </tr>
                                 @endforeach
                              

                            </tbody>
                            </table>

                            <p> Total de <code>{!! count($tabela->produtos) !!}</code> itens.</p>
                          </div>
                        
                        </div>

                    </div>
                    @endif

                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-9">
                          <span class="pull-right">
                            <a id="cancel" href="{{ url('/tabelaPreco') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                            <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                          </span>
                        </div>
                    </div>

                    </form>

                    @if(isset($produtos) && isset($tabela))
                    <form id="bordero-filter" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('tabelaPreco/salvaProduto') }}" novalidate>
                        {{ csrf_field() }}

                        <input id="id" type="hidden" name="id" value="{{ isset($tabela) ? $tabela->id : "" }}">

                        <div class="x_title">
                          <h2><i class="fa fa-bars"></i> Incluir Produto </h2>
                          <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            
                              <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                <thead>
                                  <tr class="headings">
                                    <th>
                                      <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                                    </th>
                                    <th class="column-title">Descrição </th>
                                    <th class="column-title">Unidade </th>
                                    <th class="column-title">Grupo </th>
                                    <th class="bulk-actions" colspan="7">
                                      <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @if(isset($produtos))
                                    @foreach($produtos as $produto)
                                      <tr class="even pointer">
                                        <td class="a-center ">
                                          
                                            <input type="checkbox" class="flat" name="produto_id[{{$produto->id}}]" style="position: relative; opacity: 1;">
                                            
                                        </td>
                                        <td class=" ">{{$produto->descricao}}</td>
                                        <td class=" ">{{$produto->unidade->descricao}}</td>
                                        <td class=" ">{{$produto->grupo->descricao}}</td>
                                      </tr>
                                    @endforeach
                                  @endif
                                </tbody>
                              </table>

                            <p> Total de <code>{!! count($produtos) !!}</code> itens.</p>
                            
                        </div>

                        <div class="form-group">
                          <div class="col-md-3 col-md-offset-9">
                            <span class="pull-right">
                              <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Incluir Produtos</button>
                            </span>
                          </div>
                      </div>

                    </form>
                    @endif

              </div><!-- /x-content -->

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->


<!-- /datepicker -->

@endsection