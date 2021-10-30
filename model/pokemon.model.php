<?php
    require_once "utils/responses.php";

    class PokemonModel extends Model {

        private const TABLE = "pokemons";
        private const PATH_IMAGES = "images/";
        private $id = "";
        private $name = "";
        private $image_svg = "";
        private $image_png = "";
        private $token = "";

        // GET ----------
        public function getPokemonsByLimitOffset($limit, $offset) {
            $query = "SELECT id, name, image_svg, image_png FROM " . self::TABLE . " LIMIT $limit OFFSET $offset";
            $dataList = parent::executeSelectQuery($query);
            return $dataList;
        }

        public function getPokemonById($id) {
            $query = "SELECT * FROM " . self::TABLE . " WHERE id = '$id'";
            return parent::executeSelectQuery($query);
        }

        public function getPokemonByName($name) {
            $query = "SELECT * FROM " . self::TABLE . " WHERE name = '$name'";
            return parent::executeSelectQuery($query);
        }

        // POST ----------
        public function post($body) {
            $bodyArray = json_decode($body, true);

            if(!$this->existTokenInRequest($bodyArray)) {
                return Responses::error401();
            } 

			$this->token = $bodyArray['token'];
            if(!$this->isActiveToken()) {
				return Responses::error401("El token enviado no es válido o ha caducado.");
			}

            if (!Utils::requiredFieldsSatisfiedInBody($bodyArray, ["name", "image_svg", "image_png"])) {
                return Responses::error400();
            }

			$this->prepareDataToInsertOrUpdate($bodyArray);

            $pokemonId = $this->insertPokemon();
			Utils::saveAutoIncrement($pokemonId);

            if($pokemonId) {
				$result = ["id" => $pokemonId];
				return Responses::responseOk($result);
            }else{
                $message = "Error interno. No se pudo guardar.";
				return Responses::error500($message);
            }
        }

		// PUT ----------
        public function put($body) {
            $bodyArray = json_decode($body,true);

            if(!$this->existTokenInRequest($bodyArray)) {
                return Responses::error401();
            }

            $this->token = $bodyArray['token'];

            if(!$this->isActiveToken()) {
				return Responses::error401("El token enviado no es válido o ha caducado.");
			}

            if(!$this->existPokemonIdInBodyRequest($bodyArray)) {
                return Responses::error400();
            }

            $this->prepareDataToInsertOrUpdate($bodyArray);

			if($this->updatePokemon()) {
				$result = ["id" => $this->id];
				return Responses::responseOk($result);
            }else{
                $message = "Error interno. No se pudo actualizar.";
				return Responses::error500($message);
            }
        }

		// DELETE ----------
        public function delete($body) {
            $bodyArray = json_decode($body, true);

            if(!$this->existTokenInRequest($bodyArray)) {
                return Responses::error401();
            }

			$this->token = $bodyArray['token'];

            if(!$this->isActiveToken()) {
				return Responses::error401("El token enviado no es válido o ha caducado.");
			}

            if(!$this->existPokemonIdInBodyRequest($bodyArray)) {
                return Responses::error400();
            }

			$this->id = $bodyArray['id'];

            if($this->deletePokemon() && $this->deleteImages($this->id)) {
				$result = ["id" => $this->id];
				return Responses::responseOk($result);
            }else{
                $message = "Error interno. No se pudo eliminar.";
				return Responses::error500($message);
            }
        }

		// Methods ----------
        private function existTokenInRequest($datos) {
            return isset($datos['token']);
        }

        private function prepareDataToInsertOrUpdate($bodyArray) {
			$this->id = $bodyArray['id'];
            $this->name = $bodyArray['name'];
			$this->deleteImages($this->id);
            $this->image_svg = $this->processImage($bodyArray['image_svg'], $this->id);
            $this->image_png = $this->processImage($bodyArray['image_png'], $this->id);
			$this->token = $bodyArray['token'];
        }

		private function processImage($img, $id) {
			$path = $this->savePicture($id, $img);
			return $path;
		}

		private function deleteImages($id) {
			$absolute_path =  dirname(__DIR__) . '/' . self::PATH_IMAGES;
			$path_svg = $absolute_path . 'svg/' . $id . '.svg';
			$path_png = $absolute_path . 'png/' . $id . '.png';
			return unlink($path_svg) && unlink($path_png);
		}

		private function savePicture($id, $img) {
			$partes = explode(";base64,", $img);
            $mime_type = explode("/", mime_content_type($img))[1];
            $extension = explode("+", $mime_type)[0];
            $imagen_base64 = base64_decode($partes[1]);

			$absolute_path =  dirname(__DIR__) . '/' . self::PATH_IMAGES . $extension . '/';
            $relative_path =  constant('URL') . self::PATH_IMAGES . $extension . '/';
            $file_name = $this->getFileName($extension, $id);
            file_put_contents($absolute_path . $file_name, $imagen_base64);
			return $relative_path . $file_name;
		}

		private function getFileName($extension, $id) {
			$file_name = $id ?: Utils::getAutoIncrement() + 1;
            return $file_name . "." . $extension;
		}

        private function insertPokemon(){
            $query = "INSERT INTO " . self::TABLE . " (name, image_svg, image_png)
            values('" . $this->name . "','" . $this->image_svg . "','" . $this->image_png . "')"; 
            
            $pokemonId = parent::executeInsertQueryAndGetNewId($query);
            return $pokemonId ?: 0;
        }

        private function existPokemonIdInBodyRequest($datos) {
            return isset($datos['id']);
        }

        private function updatePokemon() {
            $query = "UPDATE " . self::TABLE . " SET name ='" . $this->name . "', image_svg = '" . $this->image_svg . "', image_png = '" . $this->image_png . "' WHERE id = " . $this->id; 
            $affected_rows = parent::executeUpdateDeleteQueryAndGetAmountOfAffectedRows($query);
            return $affected_rows ?: 0;
        }

        private function deletePokemon(){
            $query = "DELETE FROM " . self::TABLE . " WHERE id = '" . $this->id . "'";
            $affected_rows = parent::executeUpdateDeleteQueryAndGetAmountOfAffectedRows($query);
            return $affected_rows ?: 0;
        }

        private function isActiveToken() {
            $query = "SELECT  id, user_id, state FROM users_token WHERE token = '" . $this->token . "' AND state = 'Activo'";
            $resp = parent::executeSelectQuery($query);
            return $resp ?: 0;
        }
    }
?>