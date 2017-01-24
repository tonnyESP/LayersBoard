<?php require_once("include/template/header.inc.php");

  // By default, the main behaviour will be creating a new experiment, but we can enter in editMode
  $editMode = false;
  $experiment = null;
  $exp_id = (int) 0;
  if( isset($_GET["experiment_id"]))
  {
    $exp_id = (int) $_GET["experiment_id"];
    $editMode = true;
    $experiment = new Experiment($exp_id);
  }
  else{

  }

?>

    <script type="text/javascript">
   $(document).ready(function() {

      <?php if($editMode)
      {
        ?>
        var datasetName = $(".img-check.check > span").text();
        var datasetId = $(".img-check.check").attr("data-id");
        $('.img-check[data-id="<?=$experiment->dataset_id;?>"]').addClass("check");
        <?php
      }
      ?>


       // Sliders de las Constants
       $("#const_threads").slider({
           tooltip: 'always'
       });

       $("#const_batch").slider({
           tooltip: 'always'
       });

       $("#const_seed").slider({
           tooltip: 'always'
       });


       $("#randomizeSeedSlider").click(function() {

           var random = (Math.random() * 5000) + 1;
           $("#const_seed").slider('setValue', random, true);
           return false;
       });


       var networkEditor = CodeMirror.fromTextArea(document.getElementById("networkCode"), {
           lineNumbers: true,
           theme: "mbo",
           matchBrackets: true,
           mode: "text/x-objectivec"
       });

       var scriptEditor = CodeMirror.fromTextArea(document.getElementById("scriptCode"), {
           lineNumbers: true,
           theme: "mbo",
           matchBrackets: true,
           mode: "text/x-objectivec"
       });

       // En el momento se entra en la tab de "run" se monta la red
       $('a[href="#tab_run"]').on('shown.bs.tab', function(e) {

           var _experimentsPath = "<?=$layersBoardPath;?>/Experiments"
           var _datasetsPath = "<?=$layersBoardPath;?>/Datasets"


           var experimentName = $("#experiment_name").val();
           var constThreads = $("#const_threads").val();
           var constBatch = $("#const_batch").val();
           var constSeed = $("#const_seed").val();

           var datasetName = $(".img-check.check > span").text();
           var datasetId = $(".img-check.check").attr("data-id");

           var netfileContent = "";
           // Check values
           if (experimentName == "") {
               $("#experiment_name").val("NewExperiment");
               experimentName = $("#experiment_name").val();
           }

           $.post("services/check-experiment-name.php", {
                   experiment_name: experimentName
               },
               function(data) {
                   experimentName = data;


                   if (constThreads == "") {
                       $("#const_threads").val(2);
                       constThreads = $("#const_threads").val();
                   }

                   if (datasetName == "") {
                       $(".img-check").first().addClass("check");
                       datasetName = $(".img-check.check > span").text();
                       datasetId = $(".img-check.check").attr("data-id");
                   }

                   if (constThreads == "" ||  constBatch == "" ||  constSeed == "" || datasetName == "") {
                       alert("Set all params")
                   }

                   var div = document.createElement('div');
                   div.innerHTML = '<pre><code data-language="c" id="full_netcode"></code></pre>';
                   $("#js-full_code-wrapper").html(div);

                   // CONST BLOCK
                   $("#full_netcode").append('const{\n');
                   $("#full_netcode").append('\tlog     = "' + _experimentsPath + '/' + experimentName + '/netlog.log"\n');
                   $("#full_netcode").append('\tthreads = ' + constThreads + '\n');
                   $("#full_netcode").append('\tbatch   = ' + constBatch + '\n');
                   $("#full_netcode").append('\tseed    = ' + constSeed + '\n');
                   $("#full_netcode").append('}\n');

                   // DATA BLOCK
                   $("#full_netcode").append('data{\n');
                   $("#full_netcode").append('\tD1 [filename="' + _datasetsPath + '/' + datasetName + '/training", binary]\n');
                   $("#full_netcode").append('\tD2 [filename="' + _datasetsPath + '/' + datasetName + '/test", binary]\n');
                   $("#full_netcode").append('}\n');

                   // DATA BLOCK
                   $("#full_netcode").append(networkEditor.getValue());
                   $("#full_netcode").append('\n');

                   // SCRIPT BLOCK
                   $("#full_netcode").append(scriptEditor.getValue());
                   $("#full_netcode").append('\n');
                   $("#full_netcode").append();

                   netfileContent = $("#full_netcode").text();

                   Rainbow.color(function() {
                       console.log('Net generated');
                   });

                   Rainbow.defer = true;
                   $("#start_training").attr("disabled", false);

               }
           );

           // Revove event listener
           $("#start_training").off("click");
           // Add listener to click
           $("#start_training").on("click", function() {

              $("#start_training").attr("disabled", true);

               $.post("services/create-new-experiment.php", {
                       experiment_name: experimentName,
                       const_threads: constThreads,
                       const_batch: constBatch,
                       const_seed: constSeed,
                       network_raw: networkEditor.getValue(),
                       script_raw: scriptEditor.getValue(),
                       dataset_id: datasetId,
                       netfile: netfileContent
                   },
                   function(data) {
                        var id = data;
                        if ($.isNumeric(data)) {
                          RunExperiment(data);
                        } else {
                           alert("Error creating the new experiment");
                        }
                   }
               );
           });
       });


       // Precargo la tab de Network y luego lanzo el evento de click en la primera tab
       $('a[href="#tab_const"]').trigger('click');

       // Selección del dataset
       $(".img-check").click(function() {
           $(".img-check.check").removeClass("check");
           $(this).addClass("check");
       });

   });

    </script>

    <?php if($editMode)
    {
    ?>
      <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <p style="text-align:center">You are creating a new experiment based on your <a href="index.php?experiment_id=<?=$experiment->id;?>"><?=$experiment->name;?></a></p>
      </div>
    <?php
    }
    ?>

      <div class="container">
        <div class="row">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="tabs_listed">
          <li role="presentation"><a href="#tab_const" aria-controls="tab_const" role="tab" data-toggle="tab">Constants</a></li>
          <li role="presentation"><a href="#tab_data" aria-controls="tab_data" role="tab" data-toggle="tab">Data</a></li>
          <li role="presentation" class="active"><a href="#tab_network_and_scripts" aria-controls="tab_network_and_scripts" role="tab" data-toggle="tab">Nets&amp;Script</a></li>
          <li role="presentation"><a href="#tab_run" aria-controls="tab_run" role="tab" data-toggle="tab">Run</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

          <div role="tabpanel" class="tab-pane" id="tab_const">
            <div class="container">
              <div class="row">
                <form class="form-horizontal">
                <fieldset>


                <div style="height:30px"></div> 
                
                <!-- Name input-->
                <div class="form-group">
                  <label class="col-md-4 control-label" for="experiment_name">Experiment name</label>  
                  <div class="col-md-4">
                  <input id="experiment_name" name="experiment_name" type="text" value="<?php if($editMode) echo $experiment->name;?>" placeholder="You can set the name to easily refer it later" class="form-control input-md">
                  <span class="help-block">Default: experiment_#id</span>  
                  </div>
                </div> 

                <!-- Text input-->
                <div class="form-group">
                  <label class="col-md-4 control-label" for="const_threads">Threads</label>  
                  <div class="col-md-4">
                  <input id="const_threads" name="const_threads" placeholder="Max number of threads" data-slider-id='const_threadsSlider' type="text" data-slider-min="0" data-slider-max="4" data-slider-step="1" data-slider-value="<?php if($editMode) echo $experiment->const_threads; else echo 2;?>" value="<?php if($editMode) echo $experiment->const_threads;else echo 2;?>" />
                  <span class="help-block">Default: 4</span>  
                  </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                  <label class="col-md-4 control-label" for="const_batch">Batch</label>  
                  <div class="col-md-4">
                  <input id="const_batch" name="const_batch" placeholder="Bach size" data-slider-id='const_batchSlider' type="text" data-slider-min="0" data-slider-max="1000" data-slider-step="100" data-slider-value="<?php if($editMode) echo $experiment->const_batch;else echo 100;?>" value="<?php if($editMode) echo $experiment->const_batch;else echo 100;?>" />
                  <span class="help-block">Default: 100</span>  
                  </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                  <label class="col-md-4 control-label" for="const_seed">Seed</label>  
                  <div class="col-md-4">
                  <input id="const_seed" name="const_seed" placeholder="Fixed seed for randoms" data-slider-id='const_seedSlider' type="text" data-slider-min="0" data-slider-max="5000" data-slider-step="1"  data-slider-value="<?php if($editMode) echo $experiment->const_seed;else echo 1234;?>" value="<?php if($editMode) echo $experiment->const_seed;else echo 1234;?>" />
                  <button id="randomizeSeedSlider" class='btn btn-primary'>Randomize</button>
                  <span class="help-block">Default: 1234</span>
                  </div>
                </div>

                </fieldset>
              </form>
              </div>
            </div>
          </div>

          <div role="tabpanel" class="tab-pane" id="tab_data">
            <h4>Default datasets</h4>
          <div class="form-group container">
            <?php Dataset::FetchAllDefault(); ?>
            <?php Dataset::FetchAllPublic(); ?>

          </div>
          <br/>
          <div class="container">
              <form class="form-horizontal">
              <fieldset>

              <!-- Form Name -->
              <h4>Upload your dataset</h4>

              <!-- Prepended text-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="prependedtext">Dataset name</label>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">Datasets/</span>
                    <input id="prependedtext" name="prependedtext" class="form-control" placeholder="e.g: MNIST" type="text" required="">
                  </div>
                  
                </div>
              </div>

              <!-- File Button --> 
              <div class="form-group">
                <label class="col-md-4 control-label" for="training_file">Training set</label>
                <div class="col-md-4">
                  <input id="training_file" name="training_file" class="input-file" type="file">
                </div>
              </div>

              <!-- File Button --> 
              <div class="form-group">
                <label class="col-md-4 control-label" for="test_file">Test set</label>
                <div class="col-md-4">
                  <input id="test_file" name="test_file" class="input-file" type="file">
                </div>
              </div>

              <!-- File Button --> 
              <div class="form-group">
                <label class="col-md-4 control-label" for="imagepreview_file">Image for preview</label>
                <div class="col-md-4">
                  <input id="imagepreview_file" name="imagepreview_file" class="input-file" type="file">
                </div>
              </div>

              <!-- Multiple Checkboxes -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="is_public">Public</label>
                <div class="col-md-4">
                <div class="checkbox">
                  <label for="is_public-0">
                    <input type="checkbox" name="is_public" id="is_public-0" value="">
                    Show this dataset as public
                  </label>
                </div>
                </div>
              </div>

              </fieldset>
              </form>
            </div>

          </div>
          <div role="tabpanel" class="tab-pane active" id="tab_network_and_scripts" style="height: 70vh"> 
            <div class="col-md-6 codeEditor" style="height: 70vh">
            <div class="codeEditor-info">Networks <i class="glyphicon glyphicon-info-sign"></i>
            <br/>
              <span class="codeEditor-description">The Networks have to define at least a training data set Test and validation data sets are optional:</span>
            </div>
              <textarea id="networkCode"><?php 
              if($editMode)
              {
                echo $experiment->network_raw;
              }
              else{ ?>
network N1 {
  data tr D1
  data ts D2
  FI in
  F  f1  [numnodes=1024]
  FO out [classification]
  in->f1
  f1->out
}
<?php } ?>
             </textarea>

            </div>

            <div class="col-md-6 codeEditor" style="height: 70vh">
              <textarea id="scriptCode"><?php
              if($editMode)
              {
                echo $experiment->script_raw;
              }
              else{ ?>
script {
  // Se normalizan los valores de 0 a 1
  D1.div(255)
  D2.div(255)

  // Hacemos shifting a la entrada 
  N1.in.shift = 2


  // Learning rate, Scaled by batch size [0.0001/batch_size]
  N1.mu = 0.1

  // Ratio de ruido a cuántas muestras 1=todas las muestras, 0.5=50% de las muestras con ruido, etc...
  // Para conseguir que sea invariante a diferencias pequeñas 
  N1.noiser = 1.0   
  N1.noisesd = 0.3
  // Ir subiendo con el annealing

  // Batch Normalization, acelera bastante la convergencia. Podríamos reducir el learning rate a 0.1 por ejemplo
  N1.bn = 1

  // Momentum rate [0.9]
  N1.mmu = 0.9

  // Activation (0 Linear, 1 Relu, 2 Sigmoid, 3 ELU) [1]
  N1.act = 1


  N1.train(25)

  N1.mu = 0.01

  N1.train(25)

  N1.mu = 0.001

  N1.train(25)    
  }  
<?php } ?>
             </textarea>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="tab_run">
            <h3 style="text-align:center">Review your network before running it</h3>

            <div id="js-full_code-wrapper">
            </div>
            <button class="btn btn-primary" id="start_training">Start training</button>

            <br/> 
            <div id="RESULT"></div>
          </div>
        </div>

        </div>
      </div>

<?php require_once("include/template/footer.inc.php");?>