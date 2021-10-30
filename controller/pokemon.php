<?php
    require_once 'model/pokemon.model.php';
    require_once 'utils/responses.php';
    require_once 'utils/utils.php';

    class Pokemon {
        
        private $_pokemonModel;

        function __construct() {
            $this->_pokemonModel = new PokemonModel();
            $this->init();
        }

        function init() {

			switch($_SERVER['REQUEST_METHOD']) {
				case "GET":
					if (isset($_GET['limit']) && isset($_GET['offset'])) {
						$this->getPokemonsByLimitOffset();
					} else if(isset($_GET['id'])) {
						$this->getPokemonById();
					} else if(isset($_GET['name'])) {
						$this->getPokemonByName();
					}
					break;
				case "POST": $this->postPokemon();
					break;
				case "PUT": $this->putPokemon();
					break;
				case "DELETE": $this->deletePokemon();
					break;
				default: Utils::sendReply(Responses::error405());
			}
        }

        // GET ----------
        function getPokemonsByLimitOffset() {
            $limit = $_GET['limit'];
            $offset = $_GET['offset'];
            $pokemonsList = $this->_pokemonModel->getPokemonsByLimitOffset($limit, $offset);
			$result = $pokemonsList;
			$response = Responses::responseOk($result);
			Utils::sendReply($response);
        }

        function getPokemonById() {
            $id = $_GET['id'];
            $pokemonData = $this->_pokemonModel->getPokemonById($id);
			$result = $pokemonData;
			$response = Responses::responseOk($result);
			Utils::sendReply($response);
        }

        function getPokemonByName() {
            $name = $_GET['name'];
            $pokemonData = $this->_pokemonModel->getPokemonByName($name);
            $result = $pokemonData;
			$response = Responses::responseOk($result);
			Utils::sendReply($response);
        }

        // POST ----------
        function postPokemon() {
            $body = file_get_contents("php://input");
            $response = $this->_pokemonModel->post($body);
			Utils::sendReply($response);
        }

        // PUT ----------
        function putPokemon() {
            $body = file_get_contents("php://input");           
            $response = $this->_pokemonModel->put($body);         
            Utils::sendReply($response);
        }

        // DELETE ----------
        function deletePokemon() {
            $body = file_get_contents("php://input");
            $response = $this->_pokemonModel->delete($body);      
            Utils::sendReply($response);
        }
    }
?>