<?php
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Method, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/User.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a DELETE request.'));
        return false;
    }

    $db = $database->connect();
    $data = json_decode(file_get_contents('php://input'));

    if(isset($data->username) && isset($data->password)) {
        $user = new User($db, null, $data->username, $data->password);
    }

    if($user->login() === false) {
        echo json_encode(array('error' => "Could not log in to the server."));
        return false;
    } else {
        return true;
    }
?>