<?php

require("../include/config.php");
global $layersBoardPath;


if(!isset($_POST["experiment_id"]) || !is_numeric($_POST["experiment_id"]))
{
    die("ERROR: ".$_POST["experiment_id"]);
}

$id = $_POST["experiment_id"];

$experiment = new Experiment( (int) $id );


// If it is not running
if ( !$experiment->IsRunning())
{
    // Go to layersBoard path
    chdir($layersBoardPath);

    // And run it
    $experiment->Run();
   
    echo "ok";
}
else
{
    echo "Layers already running";
}

?>