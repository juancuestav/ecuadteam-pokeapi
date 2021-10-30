<?php 
    require_once 'model/auth.model.php';
    require_once 'utils/responses.php';
    require_once 'utils/utils.php';

    class Auth {

        private $_authModel;

        function __construct() {
            $this->_authModel = new AuthModel;
            $this->init();
        }

        function init() {

            // POST Method ----------
            if($_SERVER['REQUEST_METHOD'] === "POST") {
                $body = file_get_contents("php://input");
                $response = $this->_authModel->login($body);             
                Utils::sendReply($response);
            } else {
                Utils::sendReply(Responses::error405());
            }
        }
    }
?>