g_req_addr = "api/get.php?key=" + g_key;
g_char_conf = {
    chart: {
        zoomType: 'x'
    },
    title: {
        text: g_key,
    },
    subtitle: {
        text: '(拖动鼠标进行放大缩小)',
    },
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: { // don't display the dummy year
            second: '%Y%m%d %H:%M:%S',
            minute: '%Y%m%d %H:%M:%S',
            hour: '%Y%m%d %H',
            day: '%Y%m%d',
            month: '%Y%m',
            year: '%Y'
        },
        labels:  {
            rotation: 350
        },
    },
    yAxis: {
        title: {
            text: 'value'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        headerFormat: '{point.x:%Y%m%d %H:%M:%S}<br>',
        pointFormat: '<b>{point.y:.2f}</b>'
    },
    plotOptions: {
        area: {
            fillColor: {
                linearGradient: {
                    x1: 0,
                    y1: 0,
                    x2: 0,
                    y2: 1
                },
                stops: [
                    [0, Highcharts.getOptions().colors[0]],
                    [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                ]
            },
            marker: {
                radius: 2
            },
            lineWidth: 1,
            states: {
                hover: {
                    lineWidth: 1
                }
            },
            threshold: null
        }
    },
    
    series: []
};

function FreshChart(ret) {
    var charts = g_char_conf;
    var serie = {
        type: 'area',
        name: 'name',
        data: [],
    };

    if(ret.name)
        charts.title.text = ret.name;

    for(var i in ret.points) {
        var _pt = ret.points[i];
        serie.data.push( 
                [ parseInt(_pt[0])*1000, parseInt(_pt[1]) ]
                );
    }

    charts.series.push(serie);

    $("#container").highcharts(charts);
}

$(function () {
    $.ajax({
        url:g_req_addr,
        type: "get",
        dataType: "json",
        //async: false,
        success: function(ret) {
            if(ret.code == 0) 
                FreshChart(ret.value);
            else
                alert(ret.value);
        },
        error: function (ret) { 
            alert(ret);
        }
    });

});
