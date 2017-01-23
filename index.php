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
    // List all experiment
    Experiment::FetchAllMine();
  }

?>



<?php
require("include/template/footer.inc.php");
?>