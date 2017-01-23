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

    $experiment_path = $layersBoardPath."/Experiments/".$experiment->name;

    exec("layers ".$experiment_path."/netfile.net > /dev/null 2>/dev/null &");

    echo "ok";
}
else
{
    echo "Layers already running";
}

?>