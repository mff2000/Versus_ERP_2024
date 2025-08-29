
<div class="form-group">

    <div class="alert alert-danger alert-dismissible" id="notificationRateio" style="display:none">
        <i class="icon-exclamation-sign"></i> {!! 'Erro ao validar Rateio, por favor verifique novamente seu dados.' !!}
        <ul id="ulError">
        </ul>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 item">
        <!--Prepare a table element as the grid-->
        <table id="tblAppendGrid" style="width:100%"></table>
    </div>
</div>
<!--Reference the CSS and JS files #appendGrid-->

<link href="{{ URL::asset('css/jquery.appendGrid-1.6.2.css') }}" rel="stylesheet"/>
<script src="{{ URL::asset('js/jquery.appendGrid-1.6.2.js') }}"></script>
<script type="text/javascript">
var dataLoad = false;
// Initial the grid as you needed
$(function() {
    $('#tblAppendGrid').appendGrid({
       // Define a function to generate a customized caption
        caption: 'Itens',
        initRows: 0,
        columns: [
            { name: 'obra_id', display: 'Obra', type: 'select', displayCss: { 'width': '50%' },
            ctrlOptions: [
                <?php 
                    foreach ($obras as $key => $value ) 
                    echo "{ label: '".$value."', value: '".$key."'},"  
                ?> 
            ],
            ctrlAttr: { 'style': 'width:95%' },
                onChange: function (evt, rowIndex) {
                    //console.log('seleciona produto');
                    getDataObra(rowIndex);
                    
                }
            },
            { name: 'valor_venda', display: 'Preço', type: 'text', displayCss: { 'width': '20%' }, ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '95%', 'text-align': 'right' }, value: 0, ctrlClass:"form-control currency",
                onChange: function (evt, rowIndex) {
                    calcSubTotal(rowIndex);
                }
            },
            { name: 'imagem', display: 'Imagem', type: 'custom', displayCss: { 'width': '20%' }, value: '',
                customBuilder: function (parent, idPrefix, name, uniqueIndex) {
                    // Prepare the control ID/name by using idPrefix, column name and uniqueIndex
                    var ctrlId = idPrefix + '_' + name + '_' + uniqueIndex;
                    // Create a span as a container
                    var ctrl = document.createElement('span');
                    // Set the ID and name to container and append it to parent control which is a table cell
                    $(ctrl).attr({ id: ctrlId, name: ctrlId }).appendTo(parent);
                    // Create extra controls and add to container
                    $('<img>').css('width', '120px').appendTo(ctrl);
                   
                    // Finally, return the container control
                    return ctrl;
                },
                customGetter: function (idPrefix, name, uniqueIndex) {
                    // Prepare the control ID/name by using idPrefix, column name and uniqueIndex
                    
                },
                customSetter: function (idPrefix, name, uniqueIndex, value) {
                    // Prepare the control ID/name by using idPrefix, column name and uniqueIndex
                    
                }
            },
            { name: 'subtotal', display: 'Sub-Total', type: 'text', displayCss: { 'width': '15%' }, displayCss: { 'width': '20%' }, ctrlOptions: { }, ctrlAttr: { 'style': 'width:95%', 'disabled': 'disabled' }, value: 0, ctrlClass:"form-control currency" },
            { name: 'ItemId', type: 'hidden', value: 0 },
        ],
        <?php if(isset($venda)) { ?>
        initData: [
            <?php foreach ($venda->itens as $item) { ?>
                { 
                    'ItemId': <?=$item->id?>, 
                    'obra_id': <?=$item->obra_id?>, 
                    'valor_venda': '<?=$item->valor_obra?>',
                    'subtotal': '<?=$item->valor_obra?>',
                },
            <?php
              }
            ?>
        ],
        // Since the cascading drop down list are empty when row inserted,
        // it is required to generate options and fill values again after that.
        rowDataLoaded: function (caller, record, rowIndex, uniqueIndex) {
            // Trigger rebuild the cascade drop down list if there is a valid value assigned
            if (0 < record.obra_id) {
                var cascade1 = $('#tblAppendGrid').appendGrid('getCellCtrl', 'obra_id', rowIndex);
                $(cascade1).trigger('change');
                //$(cascade1).select2('enable', false);
                dataLoad = true;
            }
        },
        <?php
            }
        ?>
        afterRowInserted: function (caller, parentRowIndex, addedRowIndex) {
            getObras(addedRowIndex);
            allMask();
        },
        afterRowAppended: function(caller, parentRowIndex, addedRowIndex) {
            getObras(addedRowIndex);
            allMask();
        },
        hideButtons: {
            removeLast: true,
            moveUp: true,
            moveDown: true,
            insert: true
        },
        beforeRowRemove: function (caller, rowIndex) {
            return deleteItem(rowIndex);;
        },
        afterRowRemoved: function (caller, rowIndex) {
            atualizarTotal();
        }
        
    });

});


function calcSubTotal(rowIndex) {
  
    var preco = $('#tblAppendGrid').appendGrid('getCtrlValue', 'valor_venda', rowIndex);
    preco = formatFloat( preco );
  
    $('#tblAppendGrid').appendGrid('setCtrlValue', 'subtotal', rowIndex, formatMoney(preco) );

    atualizarTotal();
}

function atualizarTotal() {
    var rowCount = $('#tblAppendGrid').appendGrid('getRowCount');
    var total = 0;
    var total_itens = 0;
    //console.log("ini"+perLine)
    for(var i=0; i<rowCount; i++) {
      
        value = $('#tblAppendGrid').appendGrid('getCtrlValue', 'subtotal', i);
        value = formatFloat( value );

        // soma os valores das linhas        
        total += value;
    }

    $('#valor').val(formatMoney(total));
    $('#total_itens').val(rowCount);
}

function deleteItem(rowIndex) {
    
    var itemId = $('#tblAppendGrid').appendGrid('getCtrlValue', 'ItemId', rowIndex);
    
    if(itemId > 0) {
        $.getJSON('<?= env('APP_URL') ?>/galeria/venda/delete_item/'+itemId, function(dados) {
            if(dados.result == 'error') {
                alert(dados.message);
                window.location.reload();
            }
        });
    }

    return true;
}

function getDataObra(rowIndex) {
    
    var obra_id = $('#tblAppendGrid').appendGrid('getCtrlValue', 'obra_id', rowIndex);
    
    $.getJSON('<?= env('APP_URL') ?>/galeria/obra/ajax?obra_id='+obra_id, function(dados) {
        //console.log(dados);
        $('#tblAppendGrid').appendGrid('setCtrlValue', 'valor_venda', rowIndex, formatMoney(dados.valor_venda ) );
        $('#tblAppendGrid').appendGrid('setCtrlValue', 'subtotal', rowIndex, formatMoney(dados.valor_venda ) );
        $('#tblAppendGrid_imagem_'+(rowIndex+1)).find('img').attr('src', '/'+dados.foto);

        atualizarTotal();
    });

}

function getObras(rowIndex) {
    var venda_id = '<?= (isset($venda)) ? $venda->id : 0 ?>';
    var obras = $('#tblAppendGrid').appendGrid('getCellCtrl', 'obra_id', rowIndex);
    console.log(venda_id);
    if (venda_id == 0 || dataLoad) {

        var artista = $('#artista_id').val();

        $.getJSON('<?= env('APP_URL') ?>/galeria/obra/ajax?artista_id='+artista, function (data) {
            
            // Ajax success callback function. Populate dropdown from Json data returned from server. 
            $(obras).find('option').remove();
            $(obras).append('<option value="">--Selecione--</option>');
            for (i = 0; i < data.length; i++) {
                $(obras).append('<option value="' + data[i].id + '">' + data[i].titulo + '</option>');
            }

            allMask();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            // Ajax fail callback function. 
            alert('Error getting product type!');
        });

    }
}

</script>