<?php
require("include/template/header.inc.php");
?>

<?php


  $exp_id = (int) 0;
  if( isset($_GET["experiment_id"]))
  {
    $exp_id = (int) $_GET["experiment_id"];
  }

  // Load current experiment based on GET url paramter
  $currentExperiment = new Experiment($exp_id);
 
  $experimentIsFinished = true;

  // If we don't get the best_result in test for this experiment
  if( !isset($currentExperiment->best_result_test))
  {
    // Updates information about epocs percent
    $json = $currentExperiment->GetJsonFromLog();

    $percent_finished = ($currentExperiment->current_epocs / $currentExperiment->total_epocs) * 100;

    $experimentIsFinished = false;
    ?>

      <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <p style="text-align:center">This experiment has not finished yet </p>
      </div>
      <?php
      if(IsLayersRunning()==0)
      {
        ?>
        <br/>
        <div style="text-align:center">
          <button class="btn btn-primary" id="start_training"><span class="glyphicon glyphicon-play"></span> Start training</button>
          <button class="btn btn-primary" id="show_console"><span class="glyphicon glyphicon-list-alt"></span> Show console</button>
          <button class="btn btn-primary" id="show_graph"><span class="glyphicon glyphicon-signal"></span> Show graph</button>
          <button class="btn btn-primary" id="download_output"><span class="glyphicon glyphicon-compressed"></span> Download output</button>
          <button class="btn btn-danger" id="delete_experiment"><span class="glyphicon glyphicon-trash"></span> Remove experiment</button>  
        </div>
        <div class="clearfix"></div>

        <script src="js/d3.v3.js"></script>
        <script type="text/javascript">
         $(document).ready(function() {

            $("#start_training").off("click");
            $("#start_training").on("click", function() 
              { RunExperiment(<?=$exp_id;?>); });

            $("#delete_experiment").off("click");
            $("#delete_experiment").on("click", function() 
              { DeleteExperiment(<?=$exp_id;?>); });
   

            Rainbow.color(function() {
               console.log('Net generated');
            });

            Rainbow.defer = false;

            $.get( "services/get-json-from-log.php?experiment_id=<?=$exp_id;?>", function( data ) {
              var parsedData = $.parseJSON(data);
              InitChart(parsedData);
              InitStats(parsedData)

            });

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




         })
        </script>



        <?php
      }
      ?>

    <?php
  }

?>
          <div id="d3chart" style="text-align:center">
          <h3>Errors</h3>
          </div>



        <div class="clearfix" style="height:40px"></div>
  <div class="row">
    <div class="col-md-6">
      <div class="shell-wrap">
        <p class="shell-top-bar">Current net </p>
        <pre><code data-language="c" id="full_netcode" style="font-size:0.8em"><?=$currentExperiment->RenderCurrentNet();?>     
        </code></pre>
      </div>
    </div>
    <div class="col-md-6">
      <div class="shell-wrap">
        <p class="shell-top-bar">CLL : Console Layers Log</p>
        <div class="shell-body" id="log-text">
        <?=$currentExperiment->RenderTerminalLog();?>     
        </div>
      </div>
    </div>
  </div>


  </div>
          <div class="clearfix"></div>
          <div class="clearfix"></div>

        <div class="progressDiv">
            <div class="statChartHolder">
                <div class="progress-pie-chart" data-percent=""><!--Pie Chart -->
                    <div class="ppc-progress">
                        <div class="ppc-progress-fill"></div>
                    </div>
                    <div class="ppc-percents">
                    <div class="pcc-percents-wrapper">
                        <span>%</span>
                    </div>
                    </div>
                </div><!--End Chart -->
            </div>
            <div class="statRightHolder">
                <ul>
                <li> <h3 id="current_epocs_text"> </h3> <span>Epocs already trained</span></li>
                <li> <h3 id="total_epocs_text"> </h3> <span>Total epocs</span></li>
                </ul>
                
                    <ul class="statsLeft">
                      <li><h3 id="best_train_error_text"></h3> <span>Best training error</span></li>
                      <li><h3 id="best_test_error_text"></h3> <span>Best test error</span></li>
                    </ul>
                    <ul class="statsRight">
                      <li><h3 id="epoc_best_train_error_text"></h3> <span>Epoc when best training error</span></li>
                      <li><h3 id="epoc_best_test_error_text"></h3> <span>Epoc when best test error</span></li>
                    </ul>
            </div>
        </div>



<?php
require("include/template/footer.inc.php");
?>