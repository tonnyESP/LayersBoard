<?php

require("../include/config.php");
global $layersBoardPath;
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_POST["data"]))
{
    die("ERROR");
}

$data = $_POST["data"];
global $layersBoardPath;


$toWriteFile = $layersBoardPath."/Experiments/_tester_/test_number.ascii";

//echo $data;
//echo "<br/><br/><br/><br/>";
echo $toWriteFile;

// Write the number.test file
$handle = fopen($toWriteFile, 'w+');
if($handle)
{
    if(!fwrite($handle, $data)) 
        die("The process of creating a new test has failed");
}
fclose($handle);
// Go to layersBoard path
        // Go to layersBoard path
chdir($layersBoardPath);

// Execs layers in background and returns its pid
//exec("layers ".$experiment_path."netfile.net 2>/dev/null & echo $!", $pid);
//exec("layers /home/tonnyesp/LayersBoard/Experiments/_tester_/test.net > /dev/null 2>/dev/null ", $pid);

//print_r($pid);

$toRunNetFile = $layersBoardPath."/Experiments/_tester_/test.net";

echo "<br/>";
echo $toRunNetFile;
echo "<br/>";

exec("layers ".$toRunNetFile." 2>&1" , $resultlayers);
print_r($resultlayers);

//echo shell_exec("layers /home/tonnyesp/LayersBoard/Experiments/_tester_/test.net");
/*
foreach ($resultlayers as $r) {
    echo "Valor: $r<br />\n";
}

echo "FIN";
*/

?>