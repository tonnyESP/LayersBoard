<?php

require("../include/config.php");

if(!isset($_GET["user_id"]) || !is_numeric($_GET["user_id"]))
{
    die("ERROR");
}

$id = $_GET["user_id"];

$user = new User( (int) $id );

$user->Login();

if(isset($_SESSION["user_id"]))
	echo $_SESSION["user_id"];
else
	echo "FAIL";


?>