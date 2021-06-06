<?php
    class Database {
        //Paramaters
        private $host = '';
        private $db_name = '';
        private $username = '';
        private $password = '';
        private $conn;

        public function __construct($host, $name, $username, $password) {
            $this->host = $host;
            $this->db_name = $name;
            $this->username = $username;
            $this->password = $password;
        }

        //Connection to the DB
        public function connect() {
            $this->conn = null;

            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection Error: ' .$e->getMessage();
            }

            return $this->conn;
        }
    }
?>