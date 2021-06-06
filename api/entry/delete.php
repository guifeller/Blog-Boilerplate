<?php 
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Method, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/Entry.php';
    include_once __DIR__.'/../../config/authentication.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a DELETE request.'));
        return false;
    }

    if (!authenticate()) {
        return false;
    }

    // Connection to the database
    $db = $database->connect();

    // Get sent data 
    $data = json_decode(file_get_contents("php://input"));

    //Only creates a new entry if the required values have been sent to the server
    if(isset($data->blogid)) {
        $entry = new Entry($db, $data->blogid);
    } else {
        echo json_encode(array('message' => 'Missing data.'));
        return;
    }

    // Create the entry
    if($entry->deleteEntry()) {
        echo json_encode(array('message' => 'Entry deleted succesfully.'));
    } else {
        echo json_encode(array('message' => 'Could not delete this entry.'));
    }

?>