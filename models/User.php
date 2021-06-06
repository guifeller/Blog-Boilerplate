<?php
    class User {
        private $conn;
        private $table = 'user';

        //Properties of each user
        private $id;
        private $username;
        private $password;

        // Constructor
        public function __construct($db, $id = null, $username = null, $password = null) {
            $this->conn = $db;
            $this->id = htmlspecialchars(strip_tags($id));
            $this->username = htmlspecialchars(strip_tags($username));
            //The password is directly hashed using the bcrypt algorithm.
            $this->password = htmlspecialchars(strip_tags($password));
        }

        public function getUser() {
            //The query sent to the database
            $query = 'SELECT * FROM ' . $this->table;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
         }

        public function registerUser() {

            //The hashed password
            $hash = password_hash($this->password, PASSWORD_BCRYPT);
            
            $query = 'INSERT INTO ' . $this->table . '
            SET
                username = :username,
                password = :password';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':username', $this->username);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function login() {
            //Loads the owner's password from the database
            $query = "SELECT password, username FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $data = $stmt->fetch();
            $usrnm = $data['username'];
            $hash = $data['password'];

            if(password_verify($this->password, $hash) && $usrnm === $this->username) {
                session_start();
                // If a session already exists, resets it
                if(isset($_SESSION['username'])) {
                    session_reset();
                }
                
                // Generate user session
                $_SESSION['username'] = $data['username'];
                $_SESSION['time_last_timestamp'] = time();

                echo json_encode(array("username" => $data['username'], "sessionId" => session_id()));
                
                return true;
            }
            else {
                return false;
            }
        }

        public function updateUser() {
            $hash = password_hash($this->password, PASSWORD_BCRYPT);
            $query = 'UPDATE ' . $this->table . '
            SET
                username = :username,
                password = :password
            WHERE
                id= :id
            ';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':id', $this->id);

            // Check for errors
            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
?>