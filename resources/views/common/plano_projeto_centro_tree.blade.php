<div class="x_panel">
    <div class="x_title">
      <h2><small>Selecione os {{ $title }}</small></h2>
      <ul class="nav navbar-right panel_toolbox">
        <li>
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content" style="display: none;">

    
@if (count($contas) > 0)
    @foreach ($contas as $conta)
        @if(!$conta->conta_superior)

            <ul class="tree">
                <li class="collapsed">
                    <input type="checkbox" name="filtros[{{$table}}_id][{{$conta->id}}]" /><span>{{ $conta->codigo }} - {{ $conta->descricao }}</span>
                    
                    @if (count($conta->children) > 0)
                    <ul>

                        <?php 
                            $childrens = $conta->children;
                            $ids = array();
                        ?>
                            
                        <?php $cont = 0 ?>
                        <?php 
                            $child = $childrens[$cont];
                            $codPai = $conta->codigo.".";
                        ?>
                        
                        @while( $child )
                            

                            @if($child->deleted_at == null)
                                       
                                <li class="collapsed">
                                    <input type="checkbox" name="filtros[{{$table}}_id][{{$child->id}}]" /><span>{{ $codPai.$child->codigo }} - {{ $child->descricao }}</span>

                            
                            @endif

                            <?php

                                array_push($ids, $child->id);

                                if( count($child->children) > 0 )  {

                                    $codPai .= $child->codigo.".";

                                    $child = $child->children[0];

                                    echo '<ul>';

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

        @endif
    @endforeach
@endif
</div>
</div>
<!-- initialize checkboxTree plugin -->
<script type="text/javascript">
    //<!--
    $(document).ready(function() {
        $('.tree').tree({
            /* specify here your options */
            collapseUiIcon: 'fa fa-plus',
            expandUiIcon: 'fa fa-minus',
            leafUiIcon: 'fa fa-ellipsis-h',
            onCheck: {
                node: 'expand'
            },
            onUncheck: {
                node: 'collapse'
            }
        });
    });
//-->
</script>