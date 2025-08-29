

$(document).ready(function() {
    
    <!-- form validation -->
    // initialize the validator function
    validator.message['date'] = 'Data Inválida!';
    validator.message['invalid'] = 'invalid input';
    validator.message['checked'] = 'must be checked';
    validator.message['empty'] = 'Campo é Obrigatório.';
    validator.message['min'] = 'input is too short';
    validator.message['max'] = 'input is too long';
    validator.message['number_min'] = 'too low';
    validator.message['number_max'] = 'too high';
    validator.message['url'] = 'invalid URL';
    validator.message['number'] = 'not a number';
    validator.message['email'] = 'email address is invalid';
    validator.message['email_repeat'] = 'emails do not match';
    validator.message['password_repeat'] = 'passwords do not match';
    validator.message['repeat'] = 'no match';
    validator.message['complete'] = 'input is not complete';
    validator.message['select'] = 'Please select an option';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form.validate')
      .on('blur', 'input[required], input.optional, select.required', validator.checkField)
      .on('change', 'select.required', validator.checkField)
      .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required')
      .on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);

    $('form.validate').submit(function(e) {
      e.preventDefault();
      var submit = true;
      // evaluate the form using generic validaing
      if (!validator.checkAll($(this))) {
        submit = false;
      }

      if (submit)
        this.submit();
      return false;
    });
    <!-- /form validation -->


    //<!-- Data Picke Select -->
    $('.date- desativado').daterangepicker({
        singleDatePicker: true,
        //calender_style: "picker_2",
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY',
            "weekLabel": "W",
            "daysOfWeek": [
                "Do",
                "Se",
                "Te",
                "Qu",
                "Qt",
                "Se",
                "Sa"
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        }
      }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    $('.date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('.date-range').daterangepicker({
         "locale": {
          "format": "DD/MM/YYYY",
          'language': 'pt-BR',
          "separator": " - ",
          "applyLabel": "Aplicar",
          "cancelLabel": "Limpar",
          "fromLabel": "De",
          "toLabel": "até",
          "customRangeLabel": "Selecionar Período",
          "weekLabel": "S",
          "daysOfWeek": [
              "Dom",
              "Seg",
              "Ter",
              "Qua",
              "Qui",
              "Sex",
              "Sab"
          ],
          "monthNames": [
              "Janeiro",
              "Fevereiro",
              "Março",
              "Abril",
              "Maio",
              "Junho",
              "Julho",
              "Agosto",
              "Setembro",
              "Outubro",
              "Novembro",
              "Dezembro"
          ],
          "firstDay": 1
      },
      "showDropdowns": true,
      "autoApply": false,
      ranges: {
           'Hoje': [moment(), moment()],
           'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
           'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
           'Mês atual': [moment().startOf('month'), moment().endOf('month')],
           'Mês anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
      }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
      }).on('cancel.daterangepicker', function(ev, picker) {
        //do something, like clearing an input
        //console.log(start.toISOString(), end.toISOString(), label);
        $(this).val('');
      });
      
      $('.date-range').each(function() {

        if( $(this).attr('date') == "") {
            $(this).val('');
        }

      });
      <!-- /datepicker -->

    //<!-- input_mask -->
   
    allMask();    
    

    //<!-- select type pessoa -->
    $('#tipo_pessoa').change( function(event) {
        
        if( $(this).val() == 'F' ) {
          $('#cpf').removeAttr('disabled').css('display', 'block');
          $('#cnpj').attr('disabled', 'disabled').css('display', 'none');
        }

        if( $(this).val() == 'J' ) {
          $('#cnpj').removeAttr('disabled').css('display', 'block');
          $('#cpf').attr('disabled', 'disabled').css('display', 'none');
        }
    });

    <!-- Agendamento Repeticao -->
    $('input[type=radio][name=tipo_repeticao]').change( function(event) {
      
      $('#parcelamento').hide();
      $('#avancado').hide();

      if(this.value == 'P') {
        $('#parcelamento').show();
      }
      else if(this.value == 'A') {
        $('#parcelamento').show();
        $('#avancado').show();
      }

    });
});


function allMask() {

  $('.date').mask('00/00/0000', {placeholder: "__/__/____"});
  $('.time').mask('00:00:00');
  $('.date_time').mask('00/00/0000 00:00:00');
  $('.cep').mask('00000-000', {placeholder: "00000-000"});
  $('.phone').mask('(00) 00000-0000');
  $('.currency').mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});  
  $('.numeric').mask("###0", {reverse: true, placeholder: "000"}); //mask with dynamic syntax
  $('.percentage').mask('0##%')
  $('.cnpj').mask('00.000.000/0000-00', {reverse: true, placeholder: "00.000.000/0000-00"});
  $('.cpf').mask('000.000.000-00', {reverse: true, placeholder: "000.000.000-00"});

  $('input.date').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
  });

  $("select").select2({
    placeholder: "Selecione...",
    allowClear: true,
    
  });

  var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 14 ? '000.000.000-00' : '00.000.000/0000-00';
  },
  spOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
      }
  };

  $('.cpf_cnpj').mask(SPMaskBehavior, spOptions);

}

function copiarEnderecoFiscalParaCobranca() {

  var uf = $('#uf :selected').val();
  
  $('#cobranca_cep').val( $('#cep').val() );
  $('#cobranca_endereco').val( $('#endereco').val() );
  $('#cobranca_numero').val( $('#numero').val() );
  $('#cobranca_complemento').val( $('#complemento').val() );
  $('#cobranca_bairro').val( $('#bairro').val() );
  $('#cobranca_cidade').val( $('#cidade').val() );
  if(uf != undefined) {
    $('#cobranca_uf option[value='+uf+']').prop("selected", "selected");
    $('#cobranca_uf').select2({
      
    });
  }

}

function copiarEnderecoFiscalParaEntrega() {

  var uf = $('#uf :selected').val();
  
  $('#entrega_cep').val( $('#cep').val() );
  $('#entrega_endereco').val( $('#endereco').val() );
  $('#entrega_numero').val( $('#numero').val() );
  $('#entrega_complemento').val( $('#complemento').val() );
  $('#entrega_bairro').val( $('#bairro').val() );
  $('#entrega_cidade').val( $('#cidade').val() );
  if(uf != undefined) {
    $('#entrega_uf option[value='+uf+']').prop("selected", "selected");
    $('#entrega_uf').select2({
      
    });
  }

}

function formatMoney(value) {
  return accounting.formatMoney( value, "", 2, ".", ",");
}

function formatFloat(value) {
  return parseFloat(accounting.unformat( value, ","));
}

function setOrderBy(coluna) {

  var colunaAtual = $('#orderBy').val();
  var tipoAtual = $('#orderType').val();

  $('#orderType').val('ASC');

  if(colunaAtual == coluna) {
    if(tipoAtual == 'ASC')
      $('#orderType').val('DESC');
    else
      $('#orderType').val('ASC');
  }
  
  $('#orderBy').val(coluna);
  $('#form-filter').submit();

}
