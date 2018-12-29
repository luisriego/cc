(function () {
    $(document).ready(function () {
        var ctx1 = document.getElementById("chart1").getContext("2d");
        console.log('cargada la pagina'+ctx1);
        Chart.types.Line.extend({
            name: "LineAlt",
            "ajax": {
                'url': 'api/grafico_portada',
                'type': 'GET'
            },
            initialize: function (data) {
                var labels = [], data=[];
                console.log(data);
                Chart.types.Line.prototype.initialize.apply(this, arguments);

                var ctx = this.chart.ctx;
                var originalStroke = ctx.stroke;
                ctx1.stroke = function () {
                    ctx1.save();
                    ctx1.shadowColor = 'rgba(0, 0, 0, 0.4)';
                    ctx1.shadowBlur = 10;
                    ctx1.shadowOffsetX = 8;
                    ctx1.shadowOffsetY = 8;
                    originalStroke.apply(this, arguments)
                    ctx1.restore();

                }
            }
        });
    //     var data1 = {
    //     labels : labels,
    //     datasets : [{
    //         fillColor             : "rgba(151,187,205,0.2)",
    //         strokeColor           : "rgba(151,187,205,1)",
    //         pointColor            : "rgba(151,187,205,1)",
    //         pointStrokeColor      : "#fff",
    //         pointHighlightFill    : "#fff",
    //         pointHighlightStroke  : "rgba(151,187,205,1)",
    //         data                  : data
    //     }]
    // };


        // console.log('cargados los datos '+data1);
    });
})();


// $( document ).ready(function() {
// console.log('cargada la pagina');
//     var ctx1 = document.getElementById("chart1").getContext("2d");
//
//     $.ajax({
//         'url': 'api/grafico_portada',
//         'type': 'GET',
//     }).done(function (data) {
//         console.log(data);
//
//         // // Split timestamp and data into separate arrays
//         // var labels = [], data=[];
//         // results["packets"].forEach(function(packet) {
//         //     labels.push(new Date(packet.timestamp).formatMMDDYYYY());
//         //     data.push(parseFloat(packet.payloadString));
//         // });
//
//         // Create the chart.js data structure using 'labels' and 'data'
//
//     });
//     var data1 = {
//         labels : labels,
//         datasets : [{
//             fillColor             : "rgba(151,187,205,0.2)",
//             strokeColor           : "rgba(151,187,205,1)",
//             pointColor            : "rgba(151,187,205,1)",
//             pointStrokeColor      : "#fff",
//             pointHighlightFill    : "#fff",
//             pointHighlightStroke  : "rgba(151,187,205,1)",
//             data                  : data
//         }]
//     };
//
//     Chart.types.Line.extend({
//         name: "LineAlt",
//         initialize: function () {
//             Chart.types.Line.prototype.initialize.apply(this, arguments);
//
//             var ctx = this.chart.ctx;
//             var originalStroke = ctx.stroke;
//             ctx1.stroke = function () {
//                 ctx1.save();
//                 ctx1.shadowColor = 'rgba(0, 0, 0, 0.4)';
//                 ctx1.shadowBlur = 10;
//                 ctx1.shadowOffsetX = 8;
//                 ctx1.shadowOffsetY = 8;
//                 originalStroke.apply(this, arguments)
//                 ctx1.restore();
//
//             }
//         }
//     });
//     var chart1 = new Chart(ctx1).LineAlt(data1, {
//         scaleShowGridLines : true,
//         scaleGridLineColor : "rgba(0,0,0,.005)",
//         scaleGridLineWidth : 0,
//         scaleShowHorizontalLines: true,
//         scaleShowVerticalLines: true,
//         bezierCurve : true,
//         bezierCurveTension : 0.4,
//         pointDot : true,
//         pointDotRadius : 4,
//         pointDotStrokeWidth : 2,
//         pointHitDetectionRadius : 2,
//         datasetStroke : true,
//         tooltipCornerRadius: 2,
//         datasetStrokeWidth : 0,
//         datasetFill : false,
//         legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
//         responsive: true
//     });
// });