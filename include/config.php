<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Tonny's local mac
$serverPath = "/Volumes/Macintosh HD/Applications/XAMPP/xamppfiles/htdocs/LayersBoard/LayersBoard";
$layersBoardPath = "/Users/tonny/Documents/Layers/LayersBoard";

// Server
//$serverPath = "/var/www/html/LayersBoard";
//$layersBoardPath = "/home/tonnyesp/LayersBoard";
 
require_once("$serverPath/include/functions.inc.php");
require_once("$serverPath/include/database.inc.php");

require_once("$serverPath/include/experiment.inc.php");
require_once("$serverPath/include/dataset.inc.php");

require_once("$serverPath/include/user.inc.php");

$db = Database::getInstance();
$mysqli = $db->getConnection(); 

// Later we will ned a user-control-system, for the moment every single user will be 1:
$user_id = 0;
$user = null;

if( isset($_GET["user_id"]))
{
	$user_id = (int) $_GET["user_id"];
	$user = new User($user_id);
	$user->login();
}

if(isset($_SESSION['user']))
{
	$user = unserialize($_SESSION['user']);
	$user_id = $user->id;
}

// Easy way to show the "active" class at the selected section
$list_experiments_section = isset($list_experiments_section) ? $list_experiments_section : "";
$new_experiment_section   = isset($new_experiment_section)   ? $new_experiment_section   : "";
$test_experiment_section  = isset($test_experiment_section)  ? $test_experiment_section  : "";

?>