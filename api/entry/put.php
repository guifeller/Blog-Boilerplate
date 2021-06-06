<?php 
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Method, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/Entry.php';
    include_once __DIR__.'/../../config/authentication.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a PUT request.'));
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
    if(isset($data->title) && isset($data->blogid) &&isset($data->body) && isset($_SESSION['username'])) {
        $entry = new Entry($db, $data->blogid, $data->title, $data->body, $_SESSION['username']);
    } else {
        echo json_encode(array('message' => 'Missing data.'));
        return;
    }

    // Create the entry
    if($entry->updateEntry()) {
        echo json_encode(array('message' => 'Entry updated succesfully.'));
    } else {
        echo json_encode(array('message' => 'Could not update this entry.'));
    }

?>