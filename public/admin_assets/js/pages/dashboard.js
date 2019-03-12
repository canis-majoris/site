
    setTimeout(function(){ Materialize.toast('Welcome to Tvoyo Administrator panel!', 4000) }, 4000);
    /*setTimeout(function(){ Materialize.toast('You have 4 new notifications', 4000) }, 11000);*/
    
    
    
    // CounterUp Plugin
    
    // Peity Chart
    $.fn.peity.defaults.pie = {
        delimiter: null,
        fill: ["#26A69A", "#e0e0e0", "#b2dfdb"],
        height: null,
        radius: 8,
        width: null
    };
    $("span.pie").peity("pie")
    
    // Radar Chart
   /* var ctx3 = document.getElementById("radar-chart").getContext("2d");
    var data3 = {
        labels: ["Eat", "Drink", "Sleep", "Work", "Code", "Cycle", "Run"],
        datasets: [
            {
                label: "My First dataset",
                fillColor: "rgba(241,202,58,0.2)",
                strokeColor: "#F1CA3A",
                pointColor: "#F1CA3A",
                data: [65, 59, 90, 81, 56, 55, 40]
            },
            {
                label: "My Second dataset",
                fillColor: "rgba(83,168,251,0.2)",
                strokeColor: "#53A8FB",
                pointColor: "#53A8FB",
                data: [28, 48, 40, 19, 96, 27, 100]
            }
        ]
    };

    var myRadarChart = new Chart(ctx3).Radar(data3, {
        scaleShowLine : true,
        angleShowLineOut : true,
        scaleShowLabels : false,
        scaleBeginAtZero : true,
        angleLineColor : "rgba(0,0,0,.1)",
        angleLineWidth : 1,
        pointLabelFontFamily : "'Arial'",
        pointLabelFontStyle : "normal",
        pointLabelFontSize : 10,
        pointLabelFontColor : "#666",
        pointDot : false,
        pointDotRadius : 3,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 20,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true,
        tooltipCornerRadius: 2,
        scaleOverride: true,
        scaleSteps: 6,
        scaleStepWidth: 15,
        scaleStartValue: 0,
    });*/
    
    
    var DrawSparkline = function(url, chart_block, data = {}) {
        $.ajax({
              url: url,
              type: "post",
              datatype: 'json',
              data: data,
              error: function(a,b,c) {
                 Materialize.toast( c, 4000);
              },
              success: function(response){
                  if (!response.success) {
                  } else {
                        /*linePoints = [];
                        for (var i = 0; i < 30; i++) {
                            linePoints[i] = 0
                            $.each(response.items, function(k, v) {
                                if (v.from_today == i+1) {
                                    linePoints[i] = v.total;
                                }
                            });
                        }*/

                        var range = response.items.length;
                        var linePoints = [];
                        var xaxis = [];
                        $.each(response.items, function(k, v) {
                            //if (v.from_today == i+1) {
                                linePoints[k] = [k, v.total];
                                xaxis[k] = [k, v.datepoint];
                            //}
                        });

                        chart_block.sparkline(linePoints, {
                            type: 'line',
                            width: 'calc(100% + 4px)',
                            height: '45',
                            //chartRangeMax: 16,
                            lineColor: '#ffb74d',
                            fillColor: 'rgba(255,183,77,0.3)',
                            highlightLineColor: 'rgba(0,0,0,0)',
                            highlightSpotColor: 'rgba(0,0,0,.2)',
                            tooltip: false
                        });

                        //console.log()

                        chart_block.closest('.card').find('.stats-counter > .counter').text(response.total_count);
                  }
              },
              beforeSend: function() {
              },
              ajaxComplete: function () {    
              }
        });
        
        /*var barParent = $('#sparkline-bar').closest('.card');
        var barPoints = [0, 1, 3, 2, 1, 1, 4, 1, 2, 0, 3, 1, 3, 4, 1, 0, 2, 3, 6, 3, 4, 2, 7, 5, 2, 4, 1, 2, 6, 13, 4, 2];
        var barWidth = 6;
        $('#sparkline-bar').sparkline(barPoints, {
            type: 'bar',
            height: $('#sparkline-bar').height() + 'px',
            width: '100%',
            barWidth: barWidth,
            barSpacing: (barParent.width() - (barPoints.length * barWidth)) / barPoints.length,
            barColor: 'rgba(0,0,0,.07)',
            tooltipFormat: ' <span style="color: #ccc">&#9679;</span> {{value}}</span>'
        });*/
        
    };
    
    var resizeChart;

    /*$(window).resize(function(e) {
        clearTimeout(resizeChart);
        resizeChart = setTimeout(function() {
            DrawSparkline();
        }, 300);
    });*/
    
    var flot1 = function (url, chart_block, data = {}) {
        //var range = data.last_days;
        $.ajax({
              url: url,
              type: "post",
              datatype: 'json',
              data: data,
              error: function(a,b,c) {
                 Materialize.toast( c, 4000);
              },
              success: function(response){
                  if (!response.success) {
                  } else {
                        var range = response.items.length;
                        var linePoints = [];
                        var xaxis = [];
                        $.each(response.items, function(k, v) {
                            //if (v.from_today == i+1) {
                                linePoints[k] = [k, v.total];
                                xaxis[k] = [k, v.datepoint];
                            //}
                        });
                        /*for (var i = 0; i < range; i++) {
                            linePoints[i] = [i, 0]
                            $.each(data.items, function(k, v) {
                                if (v.from_today == i+1) {
                                    linePoints[i] = [i, v.total];
                                }
                            });
                        }*/

                        console.log(linePoints);

                        var data = [[0, 50], [1, 42], [2, 40], [3, 65], [4, 48], [5, 56], [6, 80]];
                        var data2 = [[0, 25], [1, 19], [2, 20], [3, 35], [4, 23], [5, 28], [6, 45], [7, 100]];
                        var dataset =  [
                           /* {
                                data: data,
                                color: "#E0E0E0",
                                lines: {
                                    show: true,
                                    fill: 0.4,
                                },
                                shadowSize: 0,
                            }, {
                                data: data,
                                color: "#E0E0E0",
                                lines: {
                                    show: false,
                                },
                                points: {
                                    show: true,
                                    fill: true,
                                    radius: 4,
                                    fillColor: "#fff",
                                    lineWidth: 2
                                },
                                curvedLines: {
                                    apply: false,
                                },
                                shadowSize: 0
                            },*/ {
                                data: linePoints,
                                color: "#26A69A",
                                lines: {
                                    show: true,
                                    fill: 0.4,
                                },
                                shadowSize: 2,
                            },{
                                data: linePoints,
                                color: "#26A69A",
                                lines: {
                                    show: false,
                                },
                                curvedLines: {
                                    apply: true,
                                },
                                points: {
                                    show: false,
                                    fill: true,
                                    radius: 2,
                                    fillColor: "#fff",
                                    lineWidth: 1
                                },
                                shadowSize: 0
                            }
                        ];
                        
                        var ticks = xaxis;

                        var plot1 = $.plot(chart_block, dataset, {
                            series: {
                                color: "#14D1BD",
                                lines: {
                                    show: true,
                                    fill: 0.4
                                },
                                shadowSize: 0,
                                curvedLines: {
                                    apply: true,
                                    active: true
                                }
                            },
                            xaxis: {
                                ticks: ticks,
                            },
                            legend: {
                                show: true
                            },
                            grid: {
                                color: "#AFAFAF",
                                hoverable: true,
                                borderWidth: 0,
                                backgroundColor: '#FFF'
                            },
                            tooltip: true,
                            tooltipOpts: {
                                content: "%yK",
                                defaultTheme: false
                            }
                        });
                  }
              },
              beforeSend: function() {
              },
              ajaxComplete: function () {    
              }
        });
    };




    
    
    
    /*var flotchart2 = function () {

        // We use an inline data source in the example, usually data would
        // be fetched from a server

        var data = [],
            totalPoints = 50;
        
        function getRandomData() {

            if (data.length > 0)
                data = data.slice(1);

            // Do a random walk

            while (data.length < totalPoints) {

                var prev = data.length > 0 ? data[data.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;

                if (y < 0) {
                    y = 0;
                } else if (y > 75) {
                    y = 75;
                }

                data.push(y);
            }

            // Zip the generated y values with the x values

            var res = [];
            for (var i = 0; i < data.length; ++i) {
                res.push([i, data[i]])
            }

            return res;
        }

        var plot4 = $.plot("#flotchart2", [ getRandomData() ], {
            series: {
                shadowSize: 0   // Drawing is faster without shadows
            },
            yaxis: {
                min: 0,
                max: 75
            },
            xaxis: {
                min: 0,
                max: 50
            },
            colors: ["#26A69A"],
            legend: {
                show: false
            },
            grid: {
                color: "#AFAFAF",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: '#FFF'
            },
            tooltip: true,
            tooltipOpts: {
                content: "Y: %y",
                defaultTheme: false
            }
        });

        function update() {
            plot4.setData([getRandomData()]);

            plot4.draw();
            setTimeout(update, 2000);
        }

        update();
        
    };

    flotchart2();*/


    var order_sales = function (url, chart_block, data = {}) {
        //var range = data.last_days;
        $.ajax({
              url: url,
              type: "post",
              datatype: 'json',
              data: data,
              error: function(a,b,c) {
                 Materialize.toast( c, 4000);
              },
              success: function(response){
                  if (!response.success) {
                  } else {
                   // alert(response.sum)
                    chart_block.text(response.sum);
                  }
              },
              beforeSend: function() {
              },
              ajaxComplete: function () {    
              }
        });
    };

    
    $(document).on("fixedSidebarClick", function() {
        clearTimeout(resizeChart);
        resizeChart = setTimeout(function() {
            DrawSparkline();
        }, 300);
    });
