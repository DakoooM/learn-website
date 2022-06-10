<?php 

    class Database {
        function __construct() {
            $this->db = null;
        }
    
        public function connect(string $host, string $host_user, string $db_name, string $db_port, string $host_password) {
            try {
                $this->db = new PDO('mysql:host='.$host.';port=' .$db_port. ';dbname='.$db_name, $host_user, $host_password);
                return $this->db;
            } catch(Exception $e) {
                echo "Error : ".$e->getMessage();
                return false;
            }
        }

        public function request(string $query){
            $request = $this->db->prepare($query);
            $request->execute();
            return $request->fetchAll();
        }
    }