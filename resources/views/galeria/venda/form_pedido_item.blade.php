<table class="table table-striped responsive-utilities jambo_table bulk_action">
  <thead>
    <tr class="headings">
      <th class="column-title" width="80">Imagem </th>
      <th class="column-title">Título da Obra </th>
      <th class="column-title">Valor </th>
      <th class="column-title">Sub-total </th>
      <th class="column-title no-link last"><span class="nobr">Ações</span>
      </th>
    </tr>
  </thead>

  <tbody id="itens_galeria">
    <?php 
      if(isset($consignacao)) { 
        $venda = $consignacao;
      } 
    ?>
    @if(isset($venda)) 
      @foreach($venda->itens as $item)
          <tr>
            <td><img src="{{url($item->obra->foto)}}" height="60"></td>
            <td>{{$item->obra->titulo}}</td>
            <td>{{priceFormat($item->obra->valor_venda)}}</td>
            <td class="">{{priceFormat($item->obra->valor_venda)}}</td>
            <td>
              <button type="button" onclick="deleteItem({{$item->id}}, this)" title="" data-placement="top" data-toggle="tooltip" data-original-title="Apagar Item" class="btn btn-sm tooltips"><i class="fa fa-trash-o"></i></button>
              <input type="hidden" name="obra_id[]" value="{{$item->obra->id}}" />
              <input type="hidden" class="subtotal" name="valor[]" value="{{priceFormat($item->obra->valor_venda)}}" />
            </td>
          </tr>';

      @endforeach
    @endif

  </tbody>
</table>

<script type="text/javascript">

$(document).ready(function() {

  if($('#obra_select').is("select")) {
    
    $('#obra_select').select2({
      ajax: {
        url: '<?= env('APP_URL') ?>/galeria/obra/ajax',
        dataType: 'json',
        processResults: function (data) {
          //console.log(data);
          // Tranforms the top-level key of the response object from 'items' to 'results'
            return {
              results: $.parseJSON(data.results)
            };
        }
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
      }
    }).on('select2:select', function (e) { 
        
        obra = $('#obra_select').select2('data');
        getDataObra(obra[0].id);
        $('#obra_select').val(null).trigger('change');
    });
  } 

});

function getDataObra(obraId) {
    
    $.getJSON('<?= env('APP_URL') ?>/galeria/obra/ajax?obra_id='+obraId, function(dados) {
        dados = $.parseJSON(dados.results);
        var tr = '<tr>';
        
        tr += '<td><img src="<?=env('APP_URL')?>/'+dados.foto+'" height="60"></td>';
        tr += '<td>'+dados.titulo+'</td>';
        tr += '<td>R$ '+formatMoney(dados.valor_venda)+'</td>';
        tr += '<td class="">R$ '+formatMoney(dados.valor_venda)+'</td>';
        tr += '<td>';
        tr += '<button type="button" onclick="removeItem(this)" title="" data-placement="top" data-toggle="tooltip" data-original-title="Apagar Item" class="btn btn-sm tooltips"><i class="fa fa-trash-o"></i></button>';
        tr += '<input type="hidden" name="obra_id[]" value="'+dados.id+'" />';
        tr += '<input type="hidden"class="subtotal" name="valor[]" value="'+formatMoney(dados.valor_venda)+'" />';
        tr += '</td>';
        tr += '</tr>';
        $('#itens_galeria').append(tr);
        
        atualizarTotal();
    });

}

function removeItem(button) {

  if(confirm('Deseja realmente apagar esse item?'))
    $(button).parent().parent().remove();

}

function deleteItem(itemId, button) {
    
    if(confirm('Deseja realmente apagar esse item?')) {
      if(itemId > 0) {
          $.getJSON('<?= env('APP_URL') ?>/galeria/venda/delete_item/'+itemId, function(dados) {
              if(dados.result == 'error') {
                  alert(dados.message);
                  window.location.reload();
              } else {
                $(button).parent().parent().remove();
                atualizarTotal();
              }
          });
      }
    }

    return true;
}

function atualizarTotal() {

    var total = 0;
    var total_itens = 0;

    $('#itens_galeria').find('tr').each(function() {
      
      total_itens++;

      value = $(this).find('.subtotal').val();
      value = formatFloat( value );
      total += value;
    });

    $('#valor').val(formatMoney(total));
    $('#total_itens').val(total_itens);
}
</script> 