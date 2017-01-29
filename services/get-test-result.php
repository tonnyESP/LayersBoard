<?php

require("../include/config.php");
global $layersBoardPath;


$resultfile = $layersBoardPath."/Experiments/_tester_/result_test.txt";

if($contenido = file_get_contents($resultfile))
{
	$file = fopen($resultfile, "r") 
	        or die('No-result-yet');

	//Output a line of the file until the end is reached
	while(!feof($file))
	{
	    $string = fgets($file);
	    
	    
	    echo $string;

	}
	fclose($file);
}
else
{
	die("file not found");
}


?>