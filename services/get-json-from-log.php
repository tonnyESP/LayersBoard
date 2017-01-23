<?php

require("../include/config.php");
global $layersBoardPath;

if(!isset($_GET["experiment_id"]) || !is_numeric($_GET["experiment_id"]))
{
    die("ERROR: ".$_GET["experiment_id"]);
}
$id = $_GET["experiment_id"];
$experiment = new Experiment( (int) $id );

echo $experiment->GetJsonFromLog();

?>