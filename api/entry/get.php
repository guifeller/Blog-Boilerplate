<?php 
    // Headers
    header('Access-Control-Allow-Origin: *'); 
    header('Content-Type: application/json');

    include_once __DIR__.'/../standardDB.php';
    include_once __DIR__.'/../../config/Database.php';
    include_once __DIR__.'/../../models/Entry.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode(array('error' => 'This entry point can only be accessed through a GET request.'));
        return false;
    }

    //Connection to the database
    $db = $database->connect();

    $entry = new Entry($db);
    
    // Whether the user requested one comment in particular or all of them
    $specific = false;

    // Check whether the user wanted to access all the entries or just one
    if(isset($_GET['id'])) {
        //Returns the queried entry
        $result = $entry->getEntry($_GET['id']);
        $specific = true;
    } else {
        // Get all entries
        $result = $entry->getEntries();
    }

    // Row count
    $count = $result->rowCount();

    // Checks entries
    if($count > 0) {
        $entry_arr = array();
        $entry_arr['entries'] = array();
        $entry_arr['comments'] = array();
 
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $entry_content = array(
                'blogid' => $blogid,
                'title' => $title,
                'body' => html_entity_decode($body),
                'username' => $username,
                'created' => $created
            );

            // If one blog post in particular has been requested, then the relevant comments will also be sent to the client. 
            // Also makes sure that there actually are comments by checking $commentId
            if($specific === true && isset($commentId)) {
                $comment_content = array(
                    'commentAuthor' => $commentAuthor,
                    'commentBody' => $commentBody,
                    'commentCreated' => $commentCreated,
                    'blogId' => $blogid,
                    'commentId' => $commentId
                );
                array_push($entry_arr['comments'], $comment_content);
            }
            else {
                array_push($entry_arr['entries'], $entry_content);
            }
        }
        if($specific === true) {
            // Push the data to the array
            array_push($entry_arr['entries'], $entry_content);
        }
        

        // Convert to json
        echo json_encode($entry_arr);
    } else {
        echo json_encode(
            array('message' => 'No entries available.')
        );
    }

?>