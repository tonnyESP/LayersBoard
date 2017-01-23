<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Avoids mysql injection
function CleanVar($var) {

    global $mysqli;

    $var = $mysqli->real_escape_string(utf8_decode(strip_tags(stripslashes(trim(rtrim($var))))));

  return $var;
}

// Generates a random code of $long length
function GetRandomCode($long){


    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $code = '' ; 

    while ($i <= $long-1) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $code = $code . $tmp; 
        $i++; 
    } 

    return $code; 
} 

function IsLayersRunning()
{
  // Checkea si está abierto el proceso
  exec("sudo ps -A | grep -i layers | grep -v grep", $pids);
  
  if(empty($pids)) 
  {
    return 0;
  } 
  else 
  {
    return count($pids);
  }

}

function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}



?>