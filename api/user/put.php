<?php
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Method, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/User.php';
    include_once __DIR__.'/../../config/authentication.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a DELETE request.'));
        return false;
    }

    if (!authenticate()) {
        return false;
    }

    $db = $database->connect();
    $data = json_decode(file_get_contents('php://input'));

    if(isset($data->id) && isset($data->username) && isset($data->password)) {
        $user = new User($db, $data->id, $data->username, $data->password);
    }


    if($user->updateUser()) {
        echo json_encode(array('message' => "User account succcesfully updated."));
    } else {
        echo json_encode(array('error' => "Could not update this user."));
    }
?>