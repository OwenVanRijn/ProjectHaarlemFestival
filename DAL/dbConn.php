<?php
    class dbConn
    {
        private $conn = null;

        private function construct(){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
  
            $this->conn = mysqli_connect("206.189.9.15", "hfuser", "9veW*v9BoCap", "HaarlemFestival") or die ("<br/> Could not connect to the SQL server");
        }

        private static dbConn $dbConn;

        public static function getInstance(){
            if (!isset(self::$dbConn)){
                self::$dbConn = new dbConn();
                self::$dbConn->construct();
            }

            return self::$dbConn;
        }

        public function getConn()
        {
            return $this->conn;
        }

        public function closeConn(){
            $this->conn->kill($this->conn->thread_id);
            $this->conn->close();
        }
    }