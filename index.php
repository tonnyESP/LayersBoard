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

         })
        </script>
        <script type="text/javascript" src="js/index.js"></script>



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