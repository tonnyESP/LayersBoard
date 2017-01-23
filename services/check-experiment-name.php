<?php

require("../include/config.php");

if (isset($_POST["experiment_name"]) and $_POST["experiment_name"]!="")
{
	$experiment_name = $_POST["experiment_name"];

	// Max 15 chars
	// TODO: Check in CheckExperimentName to substring 5 more chars if it is > than 10
	$experiment_name = substr($experiment_name, 0, 15);

	echo Experiment::CheckExperimentName($experiment_name);	
}
else
	die("");

?>