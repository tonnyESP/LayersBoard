<?php

require("../include/config.php");

if(!isset($_GET["experiment_id"]) || !is_numeric($_GET["experiment_id"]))
{
    die("ERROR: experiment id required");
}

$id = $_GET["experiment_id"];

$experiment = new Experiment( (int) $id );

// Filename to be downloaded (generate a random uniqid() )
// Available at /download/ folder
// We will need a cron to remove zips occassionaly
$filename = "download_experiment_".uniqid().".zip";
$zip_file_name = '../download/'.$filename;

// Experiment folder
$experiment_path = $layersBoardPath."/Experiments/".$experiment->name;

$za = new FlxZipArchive;
$res = $za->open($zip_file_name, ZipArchive::CREATE);
if($res === TRUE)    {
    $za->addDir($experiment_path, basename($experiment_path)); $za->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='$zip_file_name'");
    header('Content-Length: ' . filesize($zipname));
    header("Location: $zip_file_name");
}
else  { echo 'Could not create a zip archive';}





?>