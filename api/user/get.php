<?php
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: GET');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Method, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/User.php';
    include_once __DIR__.'/../../config/authentication.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a GET request.'));
        return false;
    }

    if (!authenticate()) {
        return false;
    }

    $db = $database->connect();

    $user = new User($db);

    $result = $user->getUser()->fetch();
    extract($result);
    $return = array(
        "id" => $id,
        "username" => $username,
        "password" => $password
    );
    echo json_encode($return);
?>