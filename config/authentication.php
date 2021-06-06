<?php 
// Manages the authentication system's business logic.
function authenticate() {
    session_start();
    $data = json_decode(file_get_contents("php://input"));
    $time = time();

    if(!isset($_SESSION["username"]) || $data->sessionId !== session_id()) {
        echo json_encode(array('error' => "You are not logged in."));
        return false;
    }

    if($time - $_SESSION['time_last_timestamp'] >= 900) {
        echo json_encode(array('error' => "Your session expired. Please log in again."));
        return false;
    } else {
        $_SESSION['time_last_timestamp'] = time();
    }
    return true;
}

?>