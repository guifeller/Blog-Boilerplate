<?php
    class Comment {
        private $conn;
        private $table = 'comment';

        //Properties of each comment
        private $commentId;
        private $commentBody;
        private $commentAuthor;
        private $blogid;

        // Constructor
        public function __construct($db, $id = null, $body = null, $author = null, $blogid = null) {
            $this->conn = $db;

            //Since these values will be sent to the database, they need to be secured.
            $this->commentId = htmlspecialchars(strip_tags($id));
            $this->commentBody = htmlspecialchars(strip_tags($body));
            $this->commentAuthor = htmlspecialchars(strip_tags($author));
            $this->blogid = htmlspecialchars(strip_tags($blogid));
        }

        // Returns all the comments
        public function getComments() {
            //The query sent to the database
            $query = 'SELECT * FROM ' . $this->table;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        // Returns only one Comment
        public function getComment($commentId) {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE commentId=:id';
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $commentId);

            $stmt->execute();

            return $stmt;
        }

        // Create new comment
        public function createComment() {
            $query = 'INSERT INTO ' . $this->table . '
            SET
                blogid = :blogid,
                commentBody = :body,
                commentAuthor = :author
            ';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':blogid', $this->blogid);
            $stmt->bindParam(':body', $this->commentBody);
            $stmt->bindParam(':author', $this->commentAuthor);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update comment
        public function updateComment() {
            $query = 'UPDATE ' . $this->table . '
            SET
                commentBody = :body,
                commentAuthor = :author
            WHERE
                commentId= :id
            ';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':body', $this->commentBody);
            $stmt->bindParam(':author', $this->commentAuthor);
            $stmt->bindParam(':id', $this->commentId);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete comment
        public function deleteComment() {
            $query = "DELETE FROM " . $this->table . " 
            WHERE commentId= :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->commentId);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }   
    }
?>