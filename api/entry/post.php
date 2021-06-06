<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/Entry.php';
    include_once __DIR__.'/../../config/authentication.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a POST request.'));
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
    if(isset($data->title) && isset($data->body) && isset($_SESSION['username'])) {
        $entry = new Entry($db, null, $data->title, $data->body, $_SESSION['username']);
    } else {
        echo json_encode(array('message' => 'Missing data.'));
        return;
    }

    // Create the entry
    if($entry->createEntry()) {
        echo json_encode(array('message' => 'Entry added succesfully.'));
    } else {
        echo json_encode(array('message' => 'Could not add this entry.'));
    }

?>