<?php
	class App {

		function __construct() {
			$url = $_GET['url'];
			$url = rtrim($url, '/');
			$url = explode('/', $url);
			
			if ($url[0] === "") {
				require 'view/main/index.php';
			} else {
				$archivoController = 'controller/' . $url[0] . '.php';
				
				if (file_exists($archivoController)) {
					require_once $archivoController;
					$controller = new $url[0]();
					
					if ($controller instanceof Controller) {
						$controller = new $url[0]();
						$controller->render();
					}
				} else {
					require_once 'controller/error404.php';
					$controller = new Error404();
					$controller->render();
				}
			}
		}
	}
?>