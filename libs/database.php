<?php
    class Database {
        private $host;
        private $user;
        private $pass;
        private $db;

        private $link;

        public function __construct(){
            $this->host = constant('HOST');
            $this->user = constant('USER');
            $this->pass = constant('PASSWORD');
            $this->db = constant('DB');
        }

        public function connect($is_utf8 = null){
            if (!$this->link = mysqli_connect($this->host, $this->user, $this->pass)) {
                var_dump($this->host, $this->user, $this->pass);
                die('Error al conectarse al Servidor');
            }
            if (!mysqli_select_db($this->link, $this->db)) {
                die('Error no se ecuentra la Base de Datos');
            }
            if (is_null($is_utf8)) {
                mysqli_query($this->link, "SET NAMES 'utf8'");
            }
            return $this->link;
        }
    }
    class Database2024 {
        private $host;
        private $user;
        private $pass;
        private $db;

        private $link;

        public function __construct(){
            $this->host = constant('HOST-2024');
            $this->user = constant('USER-2024');
            $this->pass = constant('PASSWORD-2024');
            $this->db = constant('DB-2024');
        }

        public function connect($is_utf8 = null){
            if (!$this->link = mysqli_connect($this->host, $this->user, $this->pass)) {
                die('Error al conectarse al Servidor');
            }
            if (!mysqli_select_db($this->link, $this->db)) {
                die('Error no se ecuentra la Base de Datos');
            }
            if (is_null($is_utf8)) {
                mysqli_query($this->link, "SET NAMES 'utf8'");
            }
            return $this->link;
        }
    }
    class Database2025 {
        private $host;
        private $user;
        private $pass;
        private $db;

        private $link;

        public function __construct(){
            $this->host = constant('HOST-2025');
            $this->user = constant('USER-2025');
            $this->pass = constant('PASSWORD-2025');
            $this->db = constant('DB-2025');
        }

        public function connect($is_utf8 = null){
            if (!$this->link = mysqli_connect($this->host, $this->user, $this->pass)) {
                die('Error al conectarse al Servidor');
            }
            if (!mysqli_select_db($this->link, $this->db)) {
                die('Error no se ecuentra la Base de Datos');
            }
            if (is_null($is_utf8)) {
                mysqli_query($this->link, "SET NAMES 'utf8'");
            }
            return $this->link;
        }
    }

?>