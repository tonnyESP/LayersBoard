            <script src="js/d3.v3.js"></script>
            <script type="text/javascript">
             $(document).ready(function() {

                $("#start_training").off("click");
                $("#start_training").on("click", function() 
                  { RunExperiment(<?=$exp_id;?>); });

                $("#delete_experiment").off("click");
                $("#delete_experiment").on("click", function() 
                  { 
                    var confirmation = confirm("Are you sure you want to remove this experiment?")
                    if( confirmation )
                      DeleteExperiment(<?=$exp_id;?>); 
                  });


       

                $("#show_net_and_log").off("click");
				        $("#show_net_and_log").on("click", function() 
                  	{ 
                  		$(this).toggleClass("active");
                  		$("#index_net_and_log").toggle() 
                	});
       
                

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
