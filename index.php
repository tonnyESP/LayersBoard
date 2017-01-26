<?php
require("include/template/header.inc.php");
?>

<?php

  if( isset($_GET["experiment_id"]))
  {
    $exp_id = (int) $_GET["experiment_id"];

    $experiment = new Experiment($exp_id);
    $experiment->FullRender();
    include_once("include/template/scripts.index.inc.php");
  }
  else
  {
    ?>
    <div class="row">
    <h3>Currently running</h3>
    <?php 
    Experiment::FetchAllRunning();
    ?>
    </div>
    <div class="row">
    <h3>Experiments sorted by % test error</h3>
    <?php 
    Experiment::FetchAllRunning();
     ?>
    </div>
    <div class="row">
      <h3>Not started experiments</h3>
    <?php
    Experiment::FetchAllNotStarted();
    ?>
    </div>
    <?php
  }
?>



<?php
require("include/template/footer.inc.php");
?>