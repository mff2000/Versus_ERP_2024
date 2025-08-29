@extends('layouts.app')

@section('content')


        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Fluxo de Caixa<small></small></h3>
                </div>
                <div class="col-md-6">
                  <div id="reportrange" class="pull-right hide" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                  </div>
                </div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                <div style="width: 100%;">
                  <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                </div>
              </div>             

              <div class="clearfix"></div>
            </div>
          </div>

        </div>
       

{!! HTML::script('js/dashboard.js') !!}

<script>
  $(document).ready(function() {
    // [17, 74, 6, 39, 20, 85, 7],
    //[82, 23, 66, 9, 99, 6, 2]
    <?php 
      /* $fluxo['datas'][0][1]->year ?>,<?= $fluxo['datas'][0][1]->month ?>,<?= $fluxo['datas'][0][1]->subDay()->day ?>), <?= $saldoAtual */
    ?>
    var data1 = [
    <?php 
      $saldoAtual[0] = ($fluxo['saldo'] + ($fluxo['totalAreceber'][0] - $fluxo['totalApagar'][0])+$fluxo['limite']);
    ?>
    
    <?php //$fluxo['datas'][0][1]->addDay() ?>
    
    <?php foreach($fluxo['datas'] as $key =>  $data) { ?>

      <?php 
        if($key > 0) {
          $saldoAtual[$key] = $saldoAtual[$key-1];
          $saldoAtual[$key] += $fluxo['totalAreceber'][$key] - $fluxo['totalApagar'][$key];
        }
      ?>

      [gd(<?= $data[1]->year ?>,<?= $data[1]->month ?>,<?= $data[1]->day ?>), <?= $saldoAtual[$key] ?>],
    <?php } ?>
    ];

    var data2 = [
    <?php foreach($fluxo['datas'] as $key =>  $data) { ?>
      [gd(<?= $data[1]->year ?>,<?= $data[1]->month ?>,<?= $data[1]->day ?>), 17],
    <?php } ?>
    ];
    var plot =  $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
      data1
    ], {
      series: {
        lines: {
          show: true,
          fill: false
        },
        splines: {
          show: true,
          tension: 0.4,
          lineWidth: 1,
          fill: 0.4
        },
        points: {
          radius: 0,
          show: true
        },
        shadowSize: 2
      },
      grid: {
        verticalLines: true,
        hoverable: true,
        clickable: true,
        tickColor: "#d5d5d5",
        borderWidth: 1,
        color: '#fff'
      },
      colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
      xaxis: {
        tickColor: "rgba(51, 51, 51, 0.06)",
        mode: "time",
        tickSize: [7, "day"],
        //tickLength: 10,
        axisLabel: "Date",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
          //mode: "time", timeformat: "%m/%d/%y", minTickSize: [1, "day"]
      },
      yaxis: {
        ticks: 8,
        tickColor: "rgba(51, 51, 51, 0.06)"
      },
      tooltip: true,
      
    });

    function gd(year, month, day) {
      return new Date(year, month - 1, day).getTime();
    }   


    $("<div id='tooltip'></div>").css({
      position: "absolute",
      display: "none",
      border: "1px solid #fdd",
      padding: "2px",
      "background-color": "#fee",
      opacity: 0.80
    }).appendTo("body");

    $("#canvas_dahs").bind("plothover", function (event, pos, item) {
        console.log(item);
        if (item) {
          var x = item.datapoint[0].toFixed(2),
            y = item.datapoint[1].toFixed(2);

          $("#tooltip").html(item.series.label + " of " + x + " = " + y)
            .css({top: item.pageY+5, left: item.pageX+5})
            .fadeIn(200);
        } else {
          $("#tooltip").hide();
        }
     
    });


  });
</script>

<!-- datepicker -->
<script type="text/javascript">
  $(document).ready(function() {

    var cb = function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
    }

    var optionSet1 = {
      startDate: moment().subtract(29, 'days'),
      endDate: moment(),
      minDate: '01/01/2012',
      maxDate: '12/31/2015',
      dateLimit: {
        days: 60
      },
      showDropdowns: true,
      showWeekNumbers: true,
      timePicker: false,
      timePickerIncrement: 1,
      timePicker12Hour: true,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      opens: 'left',
      buttonClasses: ['btn btn-default'],
      applyClass: 'btn-small btn-primary',
      cancelClass: 'btn-small',
      format: 'MM/DD/YYYY',
      separator: ' to ',
      locale: {
        applyLabel: 'Submit',
        cancelLabel: 'Clear',
        fromLabel: 'From',
        toLabel: 'To',
        customRangeLabel: 'Custom',
        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        firstDay: 1
      }
    };
    $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
    $('#reportrange').daterangepicker(optionSet1, cb);
    $('#reportrange').on('show.daterangepicker', function() {
      console.log("show event fired");
    });
    $('#reportrange').on('hide.daterangepicker', function() {
      console.log("hide event fired");
    });
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
      console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
    });
    $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
      console.log("cancel event fired");
    });
    $('#options1').click(function() {
      $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
    });
    $('#options2').click(function() {
      $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
    });
    $('#destroy').click(function() {
      $('#reportrange').data('daterangepicker').remove();
    });
  });
</script>
@endsection
