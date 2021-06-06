<?php

//Includes the Database object
include_once __DIR__.'/../config/Database.php';

//Creation of a standard database to be exported everytime a db is needed
//TODO: Use values stored in a json file

$database = new Database('localhost', 'rest_boilerplate', 'root', '');

?>