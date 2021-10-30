<?php 
    class Responses {

        public static $response = [
            'status' => "ok",
            "result" => array()
        ];

        public static function error405() {
            $response['status'] = "error";
            $response['result'] = array(
                "code" => 405,
                "message" => "Metodo no permitido."
            );
            return $response;
        }

        public static function error200($message = "Datos incorrectos.") {
            $response['status'] = "error";
            $response['result'] = array(
                "code" => 200,
                "message" => $message
            );
            return $response;
        }

        public static function error400() {
            $response['status'] = "error";
            $response['result'] = array(
                "code" => 400,
                "message" => "Datos enviados incompletos o con formato incorrecto."
            );
            return $response;
        }

        public static function error500($message = "Error interno del servidor.") {
            $response['status'] = "error";
            $response['result'] = array(
                "code" => 500,
                "message" => $message
            );
            return $response;
        }

        public static function error401($message = "No autorizado.") {
            $response['status'] = "error";
            $response['result'] = array(
                "code" => 401,
                "message" => $message
            );
            return $response;
        }

        public static function responseOk($result = array()) {
            $response['status'] = "ok";
            $response['result'] = $result;
            return $response;
        }
    }
?>