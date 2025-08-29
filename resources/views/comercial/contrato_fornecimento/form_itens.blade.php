<div class="form-group">

    <div class="alert alert-danger alert-dismissible" id="notificationRateio" style="display:none">
        <i class="icon-exclamation-sign"></i> {!! 'Erro ao validar Itens, por favor verifique novamente seu dados.' !!}
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

// Initial the grid as you needed
$(function() {
    $('#tblAppendGrid').appendGrid({
       // Define a function to generate a customized caption
       caption: 'Itens do Contrato',
        initRows: 1,
        columns: [
            { name: 'produto_id', display: 'Produto', type: 'select', displayCss: { 'width': '30%' }, 
            ctrlOptions:  [
            	<?php 
            		foreach ($produtos as $key => $value ) 
            			echo "{ label: '".$value."', value: '".$key."' },";
            			
            	?> 
            ], ctrlAttr: { 'style': 'width:95%' }  },
            { name: 'quantidade', display: 'Quantidade', type: 'text', displayCss: { 'width': '15%' }, ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '95%', 'text-align': 'right' }, value: 1, ctrlClass:"form-control currency",
            onChange: function (evt, rowIndex) {
                    calcSubTotal(rowIndex);
                }
            },
            { name: 'preco_unitario', display: 'Preço', type: 'text', displayCss: { 'width': '15%' }, ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '95%', 'text-align': 'right' }, value: 0, ctrlClass:"form-control currency",
            onChange: function (evt, rowIndex) {
                    calcSubTotal(rowIndex);
                }
            },
            { name: 'pec_cliente', display: 'PEC Cliente', type: 'text', displayCss: { 'width': '10%' }, displayCss: { 'width': '10%' }, ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '95%', 'text-align': 'right' }, value: 0, ctrlClass:"form-control percentage",
            onChange: function (evt, rowIndex) {
                    
                }
            },
            { name: 'saldo_valor', display: 'Sub-Total', type: 'text', displayCss: { 'width': '20%' }, ctrlAttr: { 'style': 'width:95%', 'disabled': 'disabled' }, value: 0, ctrlClass:"form-control currency" },
            { name: 'RecordId', type: 'hidden', value: 0 }
        ],
        <?php if(isset($contrato)) { ?>
        initData: [
            <?php foreach ($contrato->itens as $item) { ?>
                { 
                    'RecordId': '<?=$item->id?>', 
                    'produto_id': '<?=$item->produto_id?>', 
                    'quantidade': '<?=$item->quantidade?>', 
                    'pec_cliente': '<?=$item->pec_cliente?>', 
                    'preco_unitario': '<?=$item->preco_unitario?>',
                    'saldo_valor': '<?=$item->total?>', 
                    'RecordId': '<?=$item->id?>'
                },
            <?php
              }
            ?>
        ],
        <?php
            }
        ?>
        useSubPanel: true,
        subPanelBuilder: function (cell, uniqueIndex) {
            // Create a label
            $('<span></span>').text('Comentário: ').appendTo(cell);
            // Create a text area
            $('<textarea></textarea>').css('vertical-align', 'middle').attr({
                id: 'tblAppendGrid_AdtComment_' + uniqueIndex,
                name: 'tblAppendGrid_AdtComment_' + uniqueIndex,
                rows: 1, cols: 60,
                ctrlClass:"form-control",
                displayCss: "width:100%"
            }).appendTo(cell);
        },
        afterRowAppended: function(caller, parentRowIndex, addedRowIndex) {
          allMask();
          
        },
        hideButtons: {
            removeLast: true,
            moveUp: true,
            moveDown: true,
            insert: true
        },
        afterRowRemoved: function (caller, rowIndex) {
            atualizarTotal(rowIndex);
        }
    });

});

function calcSubTotal(rowIndex) {
  
  var qtd = $('#tblAppendGrid').appendGrid('getCtrlValue', 'quantidade', rowIndex);
  qtd = parseFloat( qtd.replace(/,/g, "") );
  var preco = $('#tblAppendGrid').appendGrid('getCtrlValue', 'preco_unitario', rowIndex);
  preco = parseFloat( preco.replace(/,/g, "") );
  $('#tblAppendGrid').appendGrid('setCtrlValue', 'saldo_valor', rowIndex, (qtd*preco) );

  atualizarTotal();
}

function atualizarTotal() {
    var rowCount = $('#tblAppendGrid').appendGrid('getRowCount');
    var total = 0;
    //console.log("ini"+perLine)
    for(var i=0; i<rowCount; i++) {
      
        value = $('#tblAppendGrid').appendGrid('getCtrlValue', 'saldo_valor', i);
        value = parseFloat( value.replace(/,/g, "") );
        // soma os valores das linhas        
        total += value;
    }

    $('#valor').val(total);
}
</script>