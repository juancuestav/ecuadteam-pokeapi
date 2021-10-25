<?php
    require_once 'clases/respuestas.class.php';
    require_once 'clases/pokemon.class.php';

    $_respuestas = new respuestas;
    $_pokemon = new pokemon;


    if($_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_GET['limit']) && isset($_GET['offset'])) {
            getPokemonsByLimitOffset($_pokemon);
        } else if(isset($_GET['id'])) {
            getPokemonById($_pokemon);
        } else if(isset($_GET['name'])) {
            getPokemonByName($_pokemon);
        }
    } else if($_SERVER['REQUEST_METHOD'] == "POST") {
        postPokemon($_pokemon);
    } else if($_SERVER['REQUEST_METHOD'] == "PUT") {
        putPokemon($_pokemon);
    } else if($_SERVER['REQUEST_METHOD'] == "DELETE") {
        deletePokemon($_pokemon);
    } else {
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }

    // GET ----------
    function getPokemonsByLimitOffset($_pokemon) {
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $listaPokemons = $_pokemon->getPokemonsByLimitOffset($limit, $offset);
        header("Content-Type: application/json");
        echo json_encode($listaPokemons);
        http_response_code(200);
    }

    function getPokemonById($_pokemon) {
        $id = $_GET['id'];
        $datosPokemon = $_pokemon->getPokemonById($id);
        header("Content-Type: application/json");
        echo json_encode($datosPokemon);
        http_response_code(200);
    }

    function getPokemonByName($_pokemon) {
        $name = $_GET['name'];
        $datosPokemon = $_pokemon->getPokemonByName($name);
        header("Content-Type: application/json");
        echo json_encode($datosPokemon);
        http_response_code(200);
    }

    // POST ----------
    function postPokemon($_pokemon) {
        $postBody = file_get_contents("php://input");           // recepcion de datos de BODY
        $datosArray = $_pokemon->postPokemon($postBody);        // envio de datos al manejador
        header('Content-Type: application/json');               // devolucion de respuesta 
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }

    // PUT ----------
    function putPokemon($_pokemon) {
        $postBody = file_get_contents("php://input");           // recepcion de datos de BODY
        $datosArray = $_pokemon->putPokemon($postBody);         // envio de datos al manejador 
        header('Content-Type: application/json');               // devolucion de respuesta
        if(isset($datosArray["result"]["error_id"])) {
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }

    // DELETE ----------
    function deletePokemon($_pokemon) {
        $headers = getallheaders();                             // recepcion de datos de HEADER
        if(isset($headers["token"]) && isset($headers["pacienteId"])) {
            $send = [
                "token" => $headers["token"],
                "pacienteId" =>$headers["pacienteId"]
            ];
            $postBody = json_encode($send);
        } else {
            $postBody = file_get_contents("php://input");       // recepcion de datos de BODY
        }
        
        $datosArray = $_pokemon->deletePokemon($postBody);      // envio de datos al manejador
        header('Content-Type: application/json');               // devolucion de respuesta
        if(isset($datosArray["result"]["error_id"])) {
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }
?>