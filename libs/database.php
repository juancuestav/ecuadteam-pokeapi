<?php
    class Database {

        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        public $conexion;

        function __construct() {
            $this->server = constant('SERVER');
            $this->user = constant('USER');
            $this->password = constant('PASSWORD');
            $this->database = constant('DATABASE');
            $this->port = constant('PORT');

            $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
            if($this->conexion->connect_errno){
                echo "Algo va mal con la conexion";
                die();
            }
        }
    }
?>