<?php
    class Utils {

        public static final function sendReply($response) {
            $responseCode = $response["result"]["code"];
            header('Content-Type: application/json');
            http_response_code($responseCode);
            echo json_encode($response);
        }

        public static final function requiredFieldsSatisfiedInBody($bodyArray, $fields) {
            foreach($fields as $field) {
				if (!isset($bodyArray[$field])) {
					return false;
				}
            }
            return true;
        }

        public static final function encryptPassword($password){
            return md5($password);
        }

		public static final function saveAutoIncrement($lastIdInserted) {
			$file = '/tmp/file';
			$content = json_encode($lastIdInserted);
			file_put_contents($file, $content);
		}

		public static final function getAutoIncrement() {
			$file = '/tmp/file';
			return json_decode(file_get_contents($file), TRUE);
		}
    }
?>