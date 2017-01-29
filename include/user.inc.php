<?php

/*
**********************************************************************
* user.inc.php
**********************************************************************
*
* Changelog:
*     26/01/17 - VERY VERY FIRST VERSION 
*
**********************************************************************
*/

require_once('database.inc.php');
require_once('functions.inc.php');

///////////////////////////////////////////////////////////////////////
// User
// Class to manage user-related data. 
///////////////////////////////////////////////////////////////////////

class User
{
    // -------------------------------
    // Members
    // -------------------------------
    public $id               = 0;
    public $name             = "";
    public $role             = 0;
    public $image            = "";
    public $active           = false;

    private $conn = null;
       
    // -------------------------------
    // Constructor
    // -------------------------------
    public function __construct($id = -1)
    {
        global $mysqli;

        $this->conn = $mysqli;

        if (is_int($id) && $id > 0)
        {
            $this->FetchByID($id);
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

        $query = "SELECT * FROM `user` WHERE `id`=$id";

        // Validate results
        if ($result = $this->conn->query($query)) 
        {
            // Retreive results.
            if($data = $result->fetch_object())
            {
                $this->id          = $data->id;
                $this->name        = $data->name;
                $this->role        = $data->role;
                $this->image       = $data->image;     
                $this->active      = $data->active;

                $toReturn = true;
            }
        }

        return $toReturn;
    }

    public function FetchByObj($data)
    {
        $this->id          = $data->id;
        $this->name        = $data->name;
        $this->role        = $data->role;
        $this->image       = $data->image;     
        $this->active      = $data->active;
    }

    public function Login()
    {
        global $_SESSION;
        $_SESSION["user"] = serialize($this);
    }

    public function Logout()
    {
        session_destroy();
    }
}

?>