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
        <h3>Finished experiments</h3>

    <?php
    // List all experiment
    Experiment::FetchAllFinished();
    ?>
    </div>
    <div class="row">
      <h3>Not started experiments</h3>
    <?php
    // List all experiment
    Experiment::FetchAllNotStarted();
    ?>
    </div> 
    <div class="row">
      <h3>Not finished experiments</h3>
    <?php
    // List all experiment
    Experiment::FetchAllRunning();
    ?>
    </div>
    <?php
  }
?>



<?php
require("include/template/footer.inc.php");
?>