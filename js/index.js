function InitStats(data)
{
    var Current_epocs = 0;
    var Total_epocs = 0;
    var Best_train_error = 0;
    var Best_test_error = 0;
    var Epoc_best_train_error = 0;
    var Epoc_best_test_error = 0;
    var Percent_chart = 0;

    for (var key in data) 
    {
      Current_epocs += data[key]["Current_epocs"]
      Total_epocs += data[key]["Total_epocs"]
      Best_train_error = data[key]["Best_train_error"]
      Best_test_error = data[key]["Best_test_error"]
      Epoc_best_train_error = data[key]["Epoc_best_train_error"]
      Epoc_best_test_error = data[key]["Epoc_best_test_error"]
    }

    Percent_chart = (Current_epocs / Total_epocs) * 100.0;

    $("#current_epocs_text").html(""+Current_epocs);
    $("#total_epocs_text").html(""+Total_epocs);
    $("#best_train_error_text").html(""+Best_train_error);
    $("#best_test_error_text").html(""+Best_test_error);
    $("#epoc_best_train_error_text").html(""+Epoc_best_train_error);
    $("#epoc_best_test_error_text").html(""+Epoc_best_test_error);



    $(".progress-pie-chart").attr("data-percent", Percent_chart);

    var $ppc = $('.progress-pie-chart'),
      percent = parseInt($ppc.data('percent')),
      deg = 360*percent/100;
    if (percent > 50) {
      $ppc.addClass('gt-50');
    }
    $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
    $('.ppc-percents span').html(percent+'%');
}

function InitChart(datam)
{

    // Networks outs: (N1:out, ...)
    var outs = [];
    var modes = [];
    var epocs = [];

    var trainData = 
    {
        label: "",
        x: [],
        y: []
    }

    var testData = 
    {
        label: "",
        x: [],
        y: []
    }

    for (var key in datam) 
    {
       //console.log(' name=' + key + ' value=' + datam[0][key]);
       outs.push(key);
       for (var mode in datam[key]) 
       {
            modes.push(mode);
            if(mode == "Training")
            {
                trainData.label = "Training";
            }
            else
            {
                testData.label = "Test";
            }

            var count = 0;
            for (var errors in datam[key][mode]) 
            {

                epocs.push(errors);
                if(mode == "Training")
                {
                    trainData.x.push(count+1);
                    trainData.y.push(datam[key][mode][count]["Error"]);
                }
                else
                {
                    testData.x.push(count+1);
                    testData.y.push(datam[key][mode][count]["Error"]);
                }
                epocs.push(count);

                count ++;
            }
        }
    }

    var data = [ trainData, 
                 testData ] ;
    var xy_chart = d3_xy_chart()
        .width(window.innerWidth * 0.5)
        .height(window.innerHeight * 0.5)
        .xlabel("# Epocs")
        .ylabel("% Error") ;
    var svg = d3.select("#d3chart").append("svg")
        .datum(data)
        .call(xy_chart) ;
}


function d3_xy_chart() {
    var width = 740,  
        height = 350, 
        xlabel = "X Axis Label",
        ylabel = "Y Axis Label" ;
    
    function chart(selection) {
        selection.each(function(datasets) {
            //
            // Create the plot. 
            //
            var margin = {top: 20, right: 80, bottom: 30, left: 50}, 
                innerwidth = width - margin.left - margin.right,
                innerheight = height - margin.top - margin.bottom ;
            
            var x_scale = d3.scale.linear()
                .range([0, innerwidth])
                .domain([ d3.min(datasets, function(d) { return d3.min(d.x); }), 
                          d3.max(datasets, function(d) { return d3.max(d.x); }) ]) ;
            
            var y_scale = d3.scale.log()
                .range([innerheight, 0])
                .domain([ d3.min(datasets, function(d) { return d3.min(d.y); }),
                          d3.max(datasets, function(d) { return d3.max(d.y); }) ]) ;

            var color_scale = d3.scale.category10()
                .domain(d3.range(datasets.length)) ;

            var x_axis = d3.svg.axis()
                .scale(x_scale)
                .orient("bottom") ;

            var y_axis = d3.svg.axis()
                .scale(y_scale)
                .orient("left") ;

            var x_grid = d3.svg.axis()
                .scale(x_scale)
                .orient("bottom")
                .tickSize(-innerheight)
                .tickFormat("") ;

            var y_grid = d3.svg.axis()
                .scale(y_scale)
                .orient("left") 
                .tickSize(-innerwidth)
                .tickFormat("") ;

            var draw_line = d3.svg.line()
                .interpolate("basis")
                .x(function(d) { return x_scale(d[0]); })
                .y(function(d) { return y_scale(d[1]); }) ;

            var svg = d3.select(this)
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")") ;
            
            svg.append("g")
                .attr("class", "x grid")
                .attr("transform", "translate(0," + innerheight + ")")
                .call(x_grid) ;

            svg.append("g")
                .attr("class", "y grid")
                .call(y_grid) ;

            svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + innerheight + ")") 
                .call(x_axis)
                .append("text")
                .attr("dy", "-.71em")
                .attr("x", innerwidth)
                .style("text-anchor", "end")
                .text(xlabel) ;
            
            svg.append("g")
                .attr("class", "y axis")
                .call(y_axis)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", "0.71em")
                .style("text-anchor", "end")
                .text(ylabel) ;

            var data_lines = svg.selectAll(".d3_xy_chart_line")
                .data(datasets.map(function(d) {return d3.zip(d.x, d.y);}))
                .enter().append("g")
                .attr("class", "d3_xy_chart_line") ;
            
            data_lines.append("path")
                .attr("class", "line")
                .attr("d", function(d) {return draw_line(d); })
                .attr("stroke", function(_, i) {return color_scale(i);}) ;
            
            data_lines.append("text")
                .datum(function(d, i) { return {name: datasets[i].label, final: d[d.length-1]}; }) 
                .attr("transform", function(d) { 
                    return ( "translate(" + x_scale(d.final[0]) + "," + 
                             y_scale(d.final[1]) + ")" ) ; })
                .attr("x", 3)
                .attr("dy", ".35em")
                .attr("fill", function(_, i) { return color_scale(i); })
                .text(function(d) { return d.name; }) ;

        }) ;
    }

    chart.width = function(value) {
        if (!arguments.length) return width;
        width = value;
        return chart;
    };

    chart.height = function(value) {
        if (!arguments.length) return height;
        height = value;
        return chart;
    };

    chart.xlabel = function(value) {
        if(!arguments.length) return xlabel ;
        xlabel = value ;
        return chart ;
    } ;

    chart.ylabel = function(value) {
        if(!arguments.length) return ylabel ;
        ylabel = value ;
        return chart ;
    } ;

    return chart;
}
