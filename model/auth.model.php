<?php
require_once 'utils/responses.php';

class AuthModel extends Model {

    public function login($body) {
        $bodyArray = json_decode($body, true);

        $user = $bodyArray['user'];
        $password = $bodyArray['password'];
        $encryptedPassword = Utils::encryptPassword($password);

        if (!Utils::requiredFieldsSatisfiedInBody($bodyArray, ["user", "password"])) {
            return Responses::error400();
        }

        $userData = $this->getUserData($user);

        if (!$userData) {
            return Responses::error200("El usuario $user  no está registrado.");
        }

        if (!$this->samePassword($encryptedPassword, $userData['password'])) {
            return Responses::error200("La contraseña proporcionada no es válida.");
        }

        if (!$this->isUserActive($userData)) {
            return Responses::error200("El usuario esta inactivo.");
        }

        if (!$this->tokenExistsForCurrentUser($userData['id'])) {
            $token = $this->generateToken();
            if (!$this->insertToken($userData['id'], $token)) {
                return Responses::error500("Error interno. No se pudo registrar el token.");
            }
            return $this->responseWithToken($token);
        }

        $registeredToken = $this->getCurrentSessionToken($userData['id']);
        $this->updateTokenDate($registeredToken);
        return $this->responseWithToken($registeredToken);
    }

    private function samePassword($encryptedPassword, $registeredPassword) {
        return $encryptedPassword === $registeredPassword;
    }

    private function isUserActive($userData) {
        return $userData['state'] == "Activo";
    }

    // User ----------
    private function getUserData($email) {
        $query = "SELECT id, password, state FROM users WHERE user = '$email'";
        $datos = parent::executeSelectQuery($query);
        return isset($datos[0]) ? $datos[0] : 0;
    }

    public function saveUserAccount($body) {
        $bodyArray = json_decode($body, true);

        if (!Utils::requiredFieldsSatisfiedInBody($bodyArray, ["user", "password", "confirm-password"])) {
            return Responses::error400();
        }

        $user = $bodyArray['user'];
        $password = $bodyArray['password'];
        $encryptedPassword = Utils::encryptPassword($password);
        $userData = $this->getUserData($user);
        
        if ($userData) {
            $result = ["message" => "El usuario $user  ya está registrado."];
            return Responses::responseOk($result);
        } 

        $id = $this->insertUser($user, $encryptedPassword);
        if ($id) {
            $result = ["message" => "Registro exitoso."];
            return Responses::responseOk($result);
        } else {
            $message = "Error interno. No se pudo guardar.";
            return Responses::error500($message);
        }
    }

    private function insertUser($user, $password) {
        $query = "INSERT INTO users(user, password, state) VALUES('" . $user . "', '" . $password . "', 'Activo')";
        $id = parent::executeInsertQueryAndGetNewId($query);
        return $id ?: 0;
    }

    // Token ----------
    private function generateToken() {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    private function insertToken($user_id, $token) {
        $query = "INSERT INTO users_token (user_id, token, state, date) VALUES('$user_id', '$token', 'Activo', CURDATE())";
        $id = parent::executeInsertQueryAndGetNewId($query);
        return $id ? $token : 0;
    }

    private function updateTokenDate($token) {
        $query = "UPDATE users_token SET date = CURDATE() WHERE token = '$token' ";
        $affected_rows = parent::executeUpdateDeleteQueryAndGetAmountOfAffectedRows($query);
        return $affected_rows;
    }

    private function tokenExistsForCurrentUser($user_id) {
        $currentToken = $this->getCurrentSessionToken($user_id);
        return $currentToken ?: 0;
    }

	private function getCurrentSessionToken($user_id) {
        $query = "SELECT token FROM users_token WHERE user_id = " . $user_id;
        $token = parent::executeSelectQuery($query);
        return $token[0]["token"] ?: 0;
    }

    private function responseWithToken($token) {
        $result = ["token" => $token];
        return Responses::responseOk($result);
    }
}
