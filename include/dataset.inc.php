<?php

/*
**********************************************************************
* dataset.inc.php
**********************************************************************
*
* Changelog:
*	  18/01/17 - Primera versión (Tonny)
*
**********************************************************************
*/

require_once('database.inc.php');
require_once('functions.inc.php');

///////////////////////////////////////////////////////////////////////
// Dataset
// Class to manage dataset-related data. Easily allows to manipulate some
// of the basic data of a dataset (query related listing, insert, etc).
///////////////////////////////////////////////////////////////////////

class Dataset
{
    // -------------------------------
    // Members
    // -------------------------------
    public $id               = 0;
    public $name             = "";
    public $path             = "";
    public $user_id          = -1;
    public $image            = "";
    public $public           = false;
    public $isDefault        = false;

    private $conn = null;
       
    // -------------------------------
    // Constructor
    // -------------------------------
    public function __construct($id = -1)
    {
        // Get the db connection link to use into this scope as private attribute of the class
        global $mysqli;

        $this->conn = $mysqli;

        if (is_int($id) && $id != -1)
        {
            $this->FetchByID($id);
        }
        else
        {
            $this->FetchByObj($id);
        }
    }




    // -------------------------------
    // Methods
    // -------------------------------

    // --- Loading methods ---

    /*
    ** Fetches the dataset data of the given dataset ID.
    ** @param $id            dataset ID to fetch.
    ** @throws               Exception if the task is invalid or the query failed.
    */
    public function FetchByID($id)
    {
        $toReturn = false;

        $query = "SELECT * FROM `dataset` WHERE `id`=$id";

        // Validate results
        if ($result = $this->conn->query($query)) 
        {
            // Retreive results.
            $data = $result->fetch_object();

            $this->id          = $data->id;
            $this->name        = $data->name;
            $this->path        = $data->path;
            $this->user_id     = $data->user_id;
            $this->image       = $data->image;     
            $this->public      = $data->public;
            $this->isDefault   = $data->isDefault;

            $toReturn = true;
        }

        return $toReturn;
    }

    public function FetchByObj($data)
    {
        $this->id          = $data->id;
        $this->name        = $data->name;
        $this->path        = $data->path;
        $this->user_id     = $data->user_id;
        $this->image       = $data->image;     
        $this->public      = $data->public;
        $this->isDefault   = $data->isDefault;
    }
    /*
    ** Inserts a new dataset
    */
    public static function CreateDataset($name, $user_id, $image, $public)
    {

        global $mysqli;

        $toReturn = false;
		
		$name = CleanVar($name);
		
        // TODO_Tonny: Guardar path en variable en config.inc.php
        $datasets_path = "/Users/tonny/Documents/Layers/Layers";
        $path = $datasets_path."/".$name."/";

        // Check if the directory exists or get a new name
        if ( !is_dir($path) )
        {
            mkdir($path);
            // mv upload to folder
        }
        else
        {
            $name = $name."_".GetRandomCode(5);
            $path = $datasets_path."/".$name."/";
        }

        $query = " INSERT INTO `dataset` (`name`, `path`, `user_id`, `image`, `public`, `isDefault` )
                   VALUES                ('$name', '$path', $user_id,  '$image',  $public,    0 )";

        // Check if everything gone ok
        if( $result = $mysqli->query($query) )
        {
            $toReturn = true;
        }

		return $toReturn;    
    }

    /*
    ** Fetches all default datasets.
    ** @param $id            dataset ID to fetch.
    */
    public static function FetchAllDefault()
    {
        global $mysqli;

        $toReturn = [];

        $query = "SELECT * FROM `dataset` WHERE `isDefault` = 1";

        // Validate results
        if ($result = $mysqli->query($query)) 
        {
            // Retreive results.
            while ($data = $result->fetch_object()) 
            {
                $dataset = new Dataset($data);
                $dataset->Render();
            }
            


            array_push($toReturn, $data);
        }

        return $toReturn;
    }

    /*
    ** Fetches all public datasets. (not default)
    ** @param $id            dataset ID to fetch.
    */
    public static function FetchAllPublic()
    {
        global $mysqli;

        $toReturn = [];

        $query = "SELECT * FROM `dataset` WHERE `public` = 1 and `isDefault` = 0";

        // Validate results
        if ($result = $mysqli->query($query)) 
        {
            // Retreive results.
            while ($data = $result->fetch_object()) 
            {
                $dataset = new Dataset($data);
                $dataset->Render();
            }
            array_push($toReturn, $data);
        }

        return $toReturn;
    }
    
    public function MakePublic()
    {
		$records = mysqli_query("UPDATE `dataset` SET public = 1 WHERE id = $this->id;"); 
	}

    public function Render()
    {
        ?>
        <div class="col-md-3">
            <label class="btn btn-primary img-check" data-id="<?php echo $this->id;?>">
                <img src="Datasets/<?php echo $this->name;?>.png" alt="..." class="img-thumbnail" />
                <br/> 
                <span><?php echo $this->name;?></span>
            </label></div>
        <?php
    } 

}

?>