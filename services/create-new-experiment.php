<?php

require("../include/config.php");

if (isset($_POST["experiment_name"]) and $_POST["experiment_name"]!="")
{
	$experiment_name = $_POST["experiment_name"];
	$netfile = $_POST['netfile'];

    $name                    = $_POST['experiment_name'];
    $const_threads           = $_POST['const_threads'];
    $const_batch             = $_POST['const_batch'];
    $const_seed              = $_POST['const_seed'];
    $network_raw             = $_POST['network_raw'];
    $script_raw              = $_POST['script_raw'];
    $dataset_id              = $_POST['dataset_id'];

	echo Experiment::CreateExperiment($name, 1, $const_threads, $const_batch, $const_seed, $network_raw, $script_raw, $dataset_id, $netfile);
}
else
	die("ERROR");

?>