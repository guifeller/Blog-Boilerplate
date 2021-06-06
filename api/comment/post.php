<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/Comment.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a POST request.'));
        return false;
    }

    // Connection to the database
    $db = $database->connect();

    // Get sent data 
    $data = json_decode(file_get_contents("php://input"));

    //Only creates a new comment if the required values have been sent to the server
    if(isset($data->blogid) && isset($data->body) && isset($data->author)) {
        $comment = new Comment($db, null, $data->body, $data->author, $data->blogid);
    } else {
        echo json_encode(array('message' => 'Missing data.'));
        return;
    }

    // Create the comment
    if($comment->createComment()) {
        echo json_encode(array('message' => 'Comment added succesfully.'));
    } else {
        echo json_encode(array('message' => 'Could not add this comment.'));
    }

?>