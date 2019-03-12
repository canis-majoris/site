
function render_chart(url, data, render_location) {
    	$.ajax({
          url: url,
          type: "post",
          datatype: 'json',
          data: data,
          error: function(a,b,c) {
             Materialize.toast(c, 4000);
          },
          success: function(data){
              if (!data.success) {
                  Materialize.toast( data.message, 4000);

              } else {
                $(render_location).html(data.html);
              }
             /* msg_text = data.message;
              Materialize.toast( data.message, 4000);*/
          }
      });
    }

function load_chart(url, data) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast(c, 4000);
        },
        success: function(response){
            if (!response.success) {
              Materialize.toast( response.message, 4000);

            } else {
                if (response.chart.misc) {
                    $.each(response.chart.misc, function(elementid, value) {
                        $('#' + elementid).text(value);
                    });
                }

                if (response.chart.html) {
                    $.each(response.chart.html, function(elementid, value) {
                        $('#' + elementid).html($.parseHTML(value));
                    });
                }

                if (response.chart.labels) {
                    data.options['xAxis']['categories'] = response.chart.labels;
                }

                if (response.data.type) {
                    data.options.chart['type'] = response.data.type;
                }

                if (response.chart.renderTo) {
                    data.options.chart['renderTo'] = response.chart.renderTo;
                }

                //data.options.series = response.chart.values;
              
                if (response.data.multiple == 1) {
                    data.options.series = response.chart.values;
                } else {
                    data.options.series = [];
                    data.options.series[0] = response.chart.values;
                    //data.options.series[0].data = response.chart.values;
                }

                if (response.chart.drilldown) {
                    //console.log(response.chart.drilldown)
                    data.options.drilldown.series = response.chart.drilldown;
                }

                current_processed_area += data.container + ',';

                var chart = new Highcharts.chart(data.options);
                // chart.theme = {
                //     colors: ['#7cb5ec', '#f7a35c', '#90ee7e', '#7798BF', '#aaeeee', '#ff0066',
                //     '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee']
                // }
            }
         /* msg_text = data.message;
          Materialize.toast( data.message, 4000);*/
        }
  });
}


Highcharts.theme = {
   // colors: ['#DDDF0D', '#7798BF', '#55BF3B', '#DF5353', '#aaeeee',
   //    '#ff0066', '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
   chart: {
      backgroundColor: 'transparent',
      borderWidth: 0,
      borderRadius: 0,
      plotBackgroundColor: null,
      plotShadow: false,
      plotBorderWidth: 0,
      marginTop: 30,
   },
   title: {
      text: '',
      style: {
         color: 'rgb(74, 88, 113)',
        /* font: '16px Lucida Grande, Lucida Sans Unicode,' +
            ' Verdana, Arial, Helvetica, sans-serif'*/
      },
      align: 'left'
   },
   subtitle: {
      style: {
         color: '#rgb(74, 88, 113)',
         font: '12px Lucida Grande, Lucida Sans Unicode,' +
            ' Verdana, Arial, Helvetica, sans-serif'
      }
   },
   xAxis: {
      gridLineWidth: 0,
      lineColor: 'rgba(74, 88, 113, 0.1)',
      tickColor: 'rgba(74, 88, 113, 0.1)',
      labels: {
         style: {
            color: '#999',
            fontWeight: 'bold'
         }
      },
      title: {
         style: {
            color: '#AAA',
            font: 'bold 12px Lucida Grande, Lucida Sans Unicode,' +
            ' Verdana, Arial, Helvetica, sans-serif'
         }
      }
   },
   yAxis: {
      alternateGridColor: null,
      minorTickInterval: null,
      gridLineColor: 'rgba(74, 88, 113, 0.1)',
      minorGridLineColor: 'rgba(74, 88, 113, 0.1)',
      lineWidth: 0,
      tickWidth: 0,
      labels: {
         style: {
            color: '#999',
            fontWeight: 'bold'
         }
      },
      title: {
         style: {
            color: '#AAA',
            font: 'bold 12px Lucida Grande, Lucida Sans Unicode,' +
            ' Verdana, Arial, Helvetica, sans-serif'
         }
      }
   },
   legend: {
      itemStyle: {
         color: 'rgb(96, 125, 139)'
      },
      itemHoverStyle: {
         color: 'rgb(99, 146, 169)'
      },
      itemHiddenStyle: {
         color: 'rgb(193, 200, 204)'
      }
   },
   labels: {
      style: {
         color: '#CCC'
      }
   },
   // tooltip: {
   //    backgroundColor: 'black',
   //    borderWidth: 0,
   //    style: {
   //       color: '#FFF'
   //    }
   // },


   plotOptions: {
      // series: {
      //    nullColor: '#444444',
      //    shadow: true
      // },
      // line: {
      //    dataLabels: {
      //       color: '#CCC'
      //    },
      //    marker: {
      //       lineColor: '#333'
      //    }
      // },
      // spline: {
      //    marker: {
      //       lineColor: '#333'
      //    }
      // },
      // scatter: {
      //    marker: {
      //       lineColor: '#333'
      //    }
      // },
      // candlestick: {
      //    lineColor: 'white'
      // }
   },

   toolbar: {
      itemStyle: {
         color: '#CCC'
      }
   },

   navigation: {
      buttonOptions: {
         symbolStroke: 'rgb(221, 221, 221)',
         hoverSymbolStroke: 'rgba(158, 158, 158, 0.1)',
         theme: {
            fill: 'transparent'/*{
               linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
               stops: [
                  [0.4, '#606060'],
                  [0.6, '#333333']
               ]
            }*/,
            stroke: '#fff'
         },
         verticalAlign: 'top',
         y: -10
      }
   },

   // scroll charts
   rangeSelector: {
      buttonTheme: {
         fill: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
               [0.4, '#888'],
               [0.6, '#555']
            ]
         },
         stroke: '#000000',
         style: {
            color: '#CCC',
            fontWeight: 'bold'
         },
         states: {
            hover: {
               fill: {
                  linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                  stops: [
                     [0.4, '#BBB'],
                     [0.6, '#888']
                  ]
               },
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            },
            select: {
               fill: {
                  linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                  stops: [
                     [0.1, '#000'],
                     [0.3, '#333']
                  ]
               },
               stroke: '#000000',
               style: {
                  color: 'yellow'
               }
            }
         }
      },
      inputStyle: {
         backgroundColor: '#333',
         color: 'silver'
      },
      labelStyle: {
         color: 'silver'
      }
   },

   navigator: {
      handles: {
         backgroundColor: '#666',
         borderColor: '#AAA'
      },
      outlineColor: '#CCC',
      maskFill: 'rgba(16, 16, 16, 0.5)',
      series: {
         color: '#7798BF',
         lineColor: '#A6C7ED'
      }
   },

   scrollbar: {
      barBackgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
         stops: [
            [0.4, '#888'],
            [0.6, '#555']
         ]
      },
      barBorderColor: '#CCC',
      buttonArrowColor: '#CCC',
      buttonBackgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
         stops: [
            [0.4, '#888'],
            [0.6, '#555']
         ]
      },
      buttonBorderColor: '#CCC',
      rifleColor: '#FFF',
      trackBackgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
         stops: [
            [0, '#000'],
            [1, '#333']
         ]
      },
      trackBorderColor: '#666'
   },

   // special colors for some of the demo examples
   legendBackgroundColor: '#fff',
   background2: '#fff',
   dataLabelsColor: '#444',
   textColor: '#E0E0E0',
   maskColor: 'rgba(255,255,255,0.3)'
};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

//////////////////////////////
///
///
///
///CHART OPTIONS
var orders_options = {
    plotOptions: {
        series: {
            shadow: true,
            fillOpacity: 0.5
        }
    },
    chart: {
        renderTo:  "orders_chart",
    },
    title: {
        //text:  "Заказы",
    },
    credits: {
        enabled: false
    },
    xAxis: {
        title: {
            text: ""
        },
        categories: []
    },
    yAxis: {
        title: {
            text: "Amount"
        },
        plotLines: [{
            value: 0,
            height: 0.5,
            width: 1,
            color: '#808080'
        }]
    },
    plotOptions: {
        series: {
            color: "#FF5722"
        }
    },
    legend: {},
    series: []
};

var new_users_options = {
    plotOptions: {
        series: {
            shadow: true,
            fillOpacity: 0.5
        }
    },
    chart: {
        renderTo:  "new_users_chart",
    },
    title: {
       // text:  "New users",
    },
    credits: {
        enabled: false
    },
    xAxis: {
        title: {
            text: ""
        },
        categories: []
    },
    yAxis: {
        title: {
            text: "Amount"
        },
        plotLines: [{
            value: 0,
            height: 0.5,
            width: 1,
            color: '#808080'
        }]
    },
    plotOptions: {
        series: {
            color: "#FF5722"
        }
    },
    legend: {},
    series: []
};

var orders_by_region_options = {
    plotOptions: {
        series: {
            shadow: false,
            fillOpacity: 0.6
        }
    },
    chart: {
        renderTo: "orders_chart",
        height: 400,

    },
    title: {
       // text:  "Заказы",
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: "Element"
        },
    },
    legend: {},
    series: []
};


var all_users_options = {
    colors: [
        'rgba(13, 195, 170, 1)',
        'rgba(252, 194, 0, 1)',
        'rgba(231, 76, 60, 1)',
        'rgba(52, 152, 219, 1)',
        'rgba(130, 3, 123, 1)',
    ],
    chart: {
        renderTo: "all_users",
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
       // text: "Пользователи",
       // align: 'left'
    },
    credits: {
        enabled: false
    },
    tooltip: {
        // pointFormat: '{point.y} <b>({point.percentage:.1f}%)</strong>'
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span>{point.name}</span>: <b>{point.y}</b>  <i>({point.percentage:.2f}% of total)</i><br/>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
               // distance: -30,
                format: '{point.y}',
                color: 'grey'
            },
            showInLegend: true
        }
    },


    legend: {},
    series: [{
        colorByPoint: true,
        data: []
    }],
    drilldown: {
        drillUpButton: {
            relativeTo: 'spacingBox',
            position: {
                y: 50,
                x: 0
            },
            theme: {
                fill: 'white',
                'stroke-width': 0,
                stroke: '#556682',
                r: 3,
                states: {
                    hover: {
                        fill: 'rgba(74, 88, 113, 0.05)'
                    },
                    select: {
                        stroke: '#039',
                        fill: 'rgba(74, 88, 113, 0.05)'
                    }
                }
            }

        },
        series: []
    }
};


var orders_products_by_region = {
    title: {
       // text: 'Сервисы'
    },
    xAxis: {
        categories: []
    },
    chart: {
        renderTo:  "orders_products",
        type: 'column'
    },
    plotOptions: {
        column: {
            stacking: 'normal'
        }
    },
    credits: {
        enabled: false
    },

    // plotOptions: {
    //     column: {
    //         //allowPointSelect: true,
    //         cursor: 'pointer',
    //         dataLabels: {
    //             enabled: ,
    //            // distance: -30,
    //             format: '{point.stack}',
    //             color: 'grey'
    //         },
    //         showInLegend: true
    //     }
    // },
    // labels: {
    //     items: [{
    //         html: 'Services',
    //         style: {
    //             left: '50px',
    //             top: '18px',
    //             color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
    //         }
    //     }]
    // },
    series: [],
}

var all_stbs_options = {
    colors: [
        'rgba(13, 195, 170, 1)',
        'rgba(252, 194, 0, 1)',
        'rgba(231, 76, 60, 1)',
        'rgba(52, 152, 219, 1)',
        'rgba(130, 3, 123, 1)',
    ],
    chart: {
        renderTo: "all_stbs",
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
       // text: "Пользователи",
       // align: 'left'
    },
    credits: {
        enabled: false
    },
    tooltip: {
        // pointFormat: '{point.y} <b>({point.percentage:.1f}%)</strong>'
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span>{point.name}</span>: <b>{point.y}</b>  <i>({point.percentage:.2f}% of total)</i><br/>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
               // distance: -30,
                format: '{point.y}',
                color: 'grey'
            },
            showInLegend: true
        }
    },


    legend: {},
    series: [{
        colorByPoint: true,
        data: []
    }],
    drilldown: {
        drillUpButton: {
            relativeTo: 'spacingBox',
            position: {
                y: 50,
                x: 0
            },
            theme: {
                fill: 'white',
                'stroke-width': 0,
                stroke: '#556682',
                r: 3,
                states: {
                    hover: {
                        fill: 'rgba(74, 88, 113, 0.05)'
                    },
                    select: {
                        stroke: '#039',
                        fill: 'rgba(74, 88, 113, 0.05)'
                    }
                }
            }

        },
        series: []
    }
};

var inline_chart_options = {
    chart: {
        renderTo:  "active_users_inline",
        height: 100,
    },
    title: {
        //text:  "Заказы",
    },
    credits: {
        enabled: false
    },
    xAxis: {
        title: {
            text: ""
        },
        categories: [],
        visible: false
    },
    yAxis: {
        title: {
            text: "Amount"
        },
        plotLines: [{
            value: 0,
            height: 0.5,
            width: 1,
            color: '#808080'
        }],

        visible: false
    },
    plotOptions: {
        series: {
            color: 'rgba(255, 255, 255, 0.3)',
            marker: {
                enabled: false
            },
            lineWidth: 1,
            lineColor: 'rgba(255, 255, 255, 0.3)',
            borderColor : 'rgba(255, 255, 255, 0.25)'
        }
    },
    legend: {
        enabled: false
    },
    exporting: { enabled: false },
    series: []
}