<?php
    class dbConn
    {
        private $conn = null;

        private function construct(){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);

            $filePath = $root . "/DAL/creds.dat"; # This uses a json file called creds.dat stored 1 folder above the site's root. This makes it inaccessible from the web. It stores the database credentials
            $file = fopen($filePath, "r") or die ("<br/> Unable to open credentials");
            $fileText = fread($file, filesize($filePath));
            fclose($file);
            $json = json_decode($fileText, true);
            $this->conn = mysqli_connect($json["DB_HOST"], $json["DB_USER"], $json["DB_PASS"], $json["DB_DB"]) or die ("<br/> Could not connect to the SQL server");
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