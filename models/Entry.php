<?php
    class Entry {
        private $conn;
        private $table = 'entry';

        //Properties of each blog post
        private $blogid;
        private $title;
        private $body;
        private $username;

        // Constructor
        public function __construct($db, $blogid = null, $title = null, $body = null, $username = null) {
            $this->conn = $db;

            //Since these values will be sent to the database, they need to be secured.
            $this->blogid = htmlspecialchars(strip_tags($blogid));
            $this->title = htmlspecialchars(strip_tags($title));
            $this->body = htmlspecialchars(strip_tags($body));
            $this->username = htmlspecialchars(strip_tags($username));
        }

        // Returns all the blog entries
        public function getEntries() {
            //The query sent to the database
            $query = 'SELECT * FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        // Return only one blog entry
        public function getEntry($id) {

            $query = 'SELECT entry.blogid, entry.title, entry.body, entry.username, entry.created,
                    comment.commentId, comment.commentAuthor, comment.commentBody, comment.blogid, comment.commentCreated
                    FROM ' . $this->table . '
                    LEFT JOIN comment ON entry.blogid = comment.blogid
                    WHERE entry.blogid = :blogid
                    ORDER BY
                    comment.commentId ASC'; 
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':blogid', $id);

            $stmt->execute();

            return $stmt;
        }

        // Create new blog entry
        public function createEntry() {
            $query = 'INSERT INTO ' . $this->table . '
            SET
                title = :title,
                body = :body,
                username = :username
            ';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':username', $this->username);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update blog entry
        public function updateEntry() {
            $query = 'UPDATE ' . $this->table . '
            SET
                title = :title,
                body = :body,
                username = :username
            WHERE
                blogid= :blogid
            ';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':blogid', $this->blogid);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete blog entry
        public function deleteEntry() {
            $query = "DELETE FROM " . $this->table . " 
            WHERE blogid= :blogid";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':blogid', $this->blogid);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }   
    }
?>