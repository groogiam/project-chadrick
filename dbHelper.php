
<?php
include('config.php');
class dbHelper
{

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function createInstance()
    {
        return new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
    }
}

?>
