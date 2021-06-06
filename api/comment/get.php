<?php 
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/Comment.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a GET request.'));
        return false;
    }

    //Connection to the database
    $db = $database->connect();

    $comment = new Comment($db);

    // Check whether the user wanted to access all the entries or just one
    if(isset($_GET['id'])) {
        //Returns the queried comment
        $result = $comment->getComment($_GET['id']);
    } else {
        // Get all entries
        $result = $comment->getComments();
    }

    // Row count
    $count = $result->rowCount();

    // Checks entries
    if($count > 0) {
        $comment_arr = array();
        $comment_arr['comment'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $comment_content = array(
                'commentId' => $commentId,
                'blogid' => $blogid,
                'commentBody' => html_entity_decode($commentBody),
                'commentAuthor' => $commentAuthor,
                'commentCreated' => $commentCreated
            );

            // Push to the data array
            array_push($comment_arr['comment'], $comment_content);
        }

        // Convert to json
        echo json_encode($comment_arr);
    } else {
        echo json_encode(
            array('message' => 'No comments available.')
        );
    }

?>