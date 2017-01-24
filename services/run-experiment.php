<?php

require("../include/config.php");
global $layersBoardPath;


if(!isset($_POST["experiment_id"]) || !is_numeric($_POST["experiment_id"]))
{
    die("ERROR: ".$_POST["experiment_id"]);
}

// If it is not running
if (IsLayersRunning() == 0)
{
    // Go to layersBoard path
    chdir($layersBoardPath);

    $id = $_POST["experiment_id"];

    $experiment = new Experiment( (int) $id );

    $experiment->Run();

    echo $experiment->process_id;
   
    echo "ok";
}
else
{
    echo "Layers already running";
}

?>