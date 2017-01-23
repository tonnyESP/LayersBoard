<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tonny's local mac
$serverPath = "/Volumes/Macintosh HD/Applications/XAMPP/xamppfiles/htdocs/LayersBoard";
$layersBoardPath = "/Users/tonny/Documents/Layers/Layers";

// Server
//$serverPath = "/var/www/html/LayersBoard";
//$layersBoardPath = "/home/tonnyesp/LayersBoard";
 
require_once("$serverPath/include/functions.inc.php");
require_once("$serverPath/include/database.inc.php");

require_once("$serverPath/include/experiment.inc.php");
require_once("$serverPath/include/dataset.inc.php");

$db = Database::getInstance();
$mysqli = $db->getConnection(); 
//$sql_query = "SELECT * FROM `dataset` WHERE `id`=1";
//$result = $mysqli->query($sql_query);
//while ($obj = $result->fetch_object()) {
    //echo "OBJ: ". $obj->name;
//}

// Later we will ned a user-control-system, for the moment every single user will be 1:
$user_id = 1;

?>