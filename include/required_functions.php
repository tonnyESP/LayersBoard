<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


///////////////// DATABASE CONNECTION ////////////////////////////
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "layers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

///////////////// Dataset Management ////////////////////////////
function GetListOfDatasets($conn)
{

	$sql = "SELECT * FROM datasets where isDefault = 1 LIMIT 8";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
    // output data of each row
    	while($row = $result->fetch_assoc()) 
    	{
    		?>
    		<div class="col-md-3"><label class="btn btn-primary"><img src="Datasets/<?php echo $row["name"];?>/<? echo $row["image"];?>" alt="..." class="img-thumbnail img-check">
    		<br/> <?php echo $row["name"];?> <input type="checkbox" name="chk1" id="item4" value="val1" class="hidden" autocomplete="off"></label></div>
	<?php
    	}
    } else 
    {
    	echo "ERROR: Datasets not found";
    }

}



?>