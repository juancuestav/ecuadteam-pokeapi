<?php require 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeAPI</title>
    <link rel="stylesheet" href="<?php echo constant('URL') . 'public/css/default.css' ?>">
</head>

<body>
    <div class="container">
        <div class="container-header">
            <a href="<?php echo constant('URL') ?>">
                <img src="<?php echo constant('URL') . 'public/images/logo.svg' ?>" height="35px" />
            </a>
            <a href="<?php echo constant('URL') . 'register' ?>">Registrarse</a>
        </div>
        <div class="container-body">
            <img src="<?php echo constant('URL') . 'public/images/pokemon.svg' ?>" height="50px" />
            <h3>Auth - login</h3>
            <div class="card">
                <div class="card-header">
                    <p>Necesario para obtener el token.</p>
                </div>
                <div class="card-body">
                    <code>
                        POST /auth
                        <br>
                        {
                        <br>
                        "user" :"",
                        <br>
                        "password": ""
                        <br>
                        }
                    </code>
                </div>
            </div>

            <h3>Pokemon</h3>
            <div class="card">
                <div class="card-header">
                    <h4>GET</h4>
                    <p>No requiere de token.</p>
                </div>
                <div class="card-body">
                    <code>
                        GET /pokemon?<strong>limit</strong>=100<strong>&offset</strong>=0
                        <br>
                        GET /pokemon?<strong>id</strong>=25
                        <br>
                        GET /pokemon?<strong>name</strong>=pikachu
                    </code>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>POST</h4>
                    <p>Requiere token definido en el <strong>BODY</strong> de la solicitud.</p>
                    <ul>
                        <li>La imagen png debe ser codificada a BASE64</li>
                    </ul>
                </div>
                <div class="card-body">
                    <code>
                        POST /pokemon
                        <br>
                        {
							<br>
							"name" : "",
							<br>
							"image_svg" : "",
							<br>
							"image_png":"",
							<br>
							"token" : ""
							<br>
                        }
                    </code>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>PUT</h4>
                    <p>Requiere token definido en el <strong>BODY</strong> de la solicitud.</p>
                    <ul>
                        <li>La imagen png debe ser codificada a BASE64</li>
                    </ul>
                </div>
                <div class="card-body">
                    <code>
                        PUT /pokemon
                        <br>
                        {
							<br>
							"id" : ""
							<br>
							"name" : "",
							<br>
							"image_svg" : "",
							<br>
							"image_png":"",
							<br>
							"token" : "" ,
							<br>
                        }
                    </code>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>DELETE</h4>
                    <p>Requiere token definido en el <strong>BODY</strong> de la solicitud.</p>
                </div>
                <div class="card-body">
                    <code>
                        DELETE /pokemon
                        <br>
                        {
							<br>
							"id" : ""
							<br>
							"token" : "",
							<br>
                        }
                    </code>
                </div>
            </div>
        </div>
        <footer>
            <p>© 2021 Copyright</p>
            <p>Developed by Ing. Juan Cuesta</p>
            <p>ECUADteam.com</p>
        </footer>
    </div>
</body>

</html>