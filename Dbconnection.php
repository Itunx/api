<?php
    
    class Database {
        private $host = 'localhost';
        private $dbname = 'e-commerce';
        private $username = 'root';
        private $password = '';
        private $conn;
      
        public function getConnection() {
          $this->conn = null;
      
          try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          } catch(\Exception $e) {
            echo 'Connection error: ' . $e->getMessage();
          }
      
          return $this->conn;
        }
      }
        
?>