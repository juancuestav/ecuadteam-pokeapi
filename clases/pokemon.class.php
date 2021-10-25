<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pokemon extends conexion {

    private const TABLE = "pokemons";
    private $id = "";
    private $name = "";
    private $image_svg = "";
    private $image_png = "";
    private $token = "";

    // GET ----------
    public function getPokemonsByLimitOffset($limit, $offset) {
        $query = "SELECT id, name FROM " . self::TABLE . " LIMIT $limit OFFSET $offset";
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
    public function postPokemon($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!$this->existTokenInRequest($datos)) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            
            if($this->isActiveToken()) {
                if(!$this->allRequiredFieldsAreInTheRequest($datos)) {
                    return $_respuestas->error_400();
                } else {
                    $this->extractBodyfromTheRequestAndSetItToTheEntity($datos);
                    
                    $pokemonId = $this->insertPokemon();
                    if($pokemonId){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array("id" => $pokemonId);
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El token enviado no es válido o ha caducado.");
            }
        }
    }

    private function existTokenInRequest($datos) {
        return isset($datos['token']);
    }

    private function allRequiredFieldsAreInTheRequest($datos) {
        return isset($datos['name']) || !isset($datos['image_svg']) || !isset($datos['image_svg']);
    }

    private function extractBodyfromTheRequestAndSetItToTheEntity($datos) {
        $this->name = $datos['name'];
        $this->image_svg = $this->procesarImagen($datos['image_svg']);
        $this->image_png = $this->procesarImagen($datos['image_png']);
        
    }

    private function procesarImagen($img) {
        $direccion = dirname(__DIR__) . '/public/images/';
        $partes = explode(";base64,", $img);
        $extension = explode("/", mime_content_type($img))[1];
        $imagen_base64 = base64_decode($partes[1]);
        $file = $direccion . uniqid() . "." . $extension;
        file_put_contents($file, $imagen_base64);
        return $file;
    }

    private function insertPokemon(){
        $query = "INSERT INTO " . self::TABLE . " (name, image_svg, image_png)
        values('" . $this->name . "','" . $this->image_svg . "','" . $this->image_png . "')"; 
        
        $pokemonId = parent::executeInsertQueryAndGetNewId($query);
        return $pokemonId ? $pokemonId : 0;
    }
    
    // PUT ----------
    public function putPokemon($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!$this->existTokenInRequest($datos)) {
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];

            if($this->isActiveToken()) {
                if(!$this->existPokemonIdInBodyRequest($datos) || !$this->allRequiredFieldsAreInTheRequest($datos)) {
                    return $_respuestas->error_400();
                } else {
                    $this->id = $datos['id'];
                    $this->extractBodyfromTheRequestAndSetItToTheEntity($datos);
                    $pokemonId = $this->updatePokemon();
                    if($pokemonId) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $pokemonId
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
    }

    private function existPokemonIdInBodyRequest($datos) {
        return isset($datos['id']);
    }

    private function updatePokemon() {
        $query = "UPDATE " . self::TABLE . " SET name ='" . $this->name . "', image_svg = '" . $this->image_svg . "', image_png = '" . $this->image_png . "' WHERE id = " . $this->id; 
        echo $query;
        $affected_rows = parent::executeQueryAndGetAmountOfAffectedRows($query);
        return $affected_rows >= 1 ? $affected_rows : 0;
        /* if($resp >= 1){
            return $resp;
        }else{
            return 0;
        } */
    }

    // DELETE ----------
    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!$this->existTokenInRequest($datos)) {
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];

            if($this->isActiveToken()){
                if(!$this->existPokemonIdInBodyRequest($datos)) {
                    return $_respuestas->error_400();
                } else {
                    $this->id = $datos['id'];
                    $resp = $this->deletePokemon();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $this->id
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
    }


    private function deletePokemon(){
        $query = "DELETE FROM " . self::TABLE . " WHERE id = '" . $this->id . "'";
        $affected_rows = parent::executeQueryAndGetAmountOfAffectedRows($query);
        return $affected_rows >= 1 ? $affected_rows : 0;
    }


    private function isActiveToken() {
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::executeSelectQuery($query);
        return $resp ? true : false;
    }

    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $affected_rows = parent::executeQueryAndGetAmountOfAffectedRows($query);
        return $affected_rows >= 1 ? $affected_rows : 0;
        /* if($resp >= 1){
            return $resp;
        }else{
            return 0;
        } */
    }
}
?>