<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo constant('URL') . 'public/css/default.css' ?>">
  <link rel="stylesheet" href="<?php echo constant('URL') . 'public/css/register.css' ?>">
  <title>PokeAPI</title>
</head>

<body>
  <div class="container">
    <form id="formRegister" method="POST">

      <div class="container-header">
        <a href="<?php echo constant('URL') ?>">
          <img src="<?php echo constant('URL') . 'public/images/logo.svg' ?>" height="35px" />
        </a>
      </div>

      <div class="container-body">
        <div class="card text-center w-40">
          <div class="card-header">
            <h3 class="form-subtitle">Formulario de registro</h3>
          </div>

          <div class="card-body">
            <div class="form-group">
              <label for="user" class="form-label">Usuario</label>
              <input type="email" class="form-input" name="user" id="user" placeholder="Ingrese un email..." required>
            </div>

            <div class="form-group">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-input" name="password" id="password" placeholder="Ingrese una contraseña..." required>
              <input type="password" class="form-input" name="confirm-password" id="confirm-password" placeholder="Confirmar contraseña..." required>
              <small id="message" style="color: #0b1d38; padding-top: 10px"></small>
            </div>

            <div class="form-group">
              <button id="register" type="submit" class="button-confirm">Registrar</button>
              <button class="button-cancel" onclick="window.location.href='<?php echo constant('URL') ?>'">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
      <footer>
        <p>© 2021 Copyright</p>
        <p>Developed by Ing. Juan Cuesta</p>
        <p>ECUADteam.com</p>
      </footer>
  </div>
  </form>
  </div>

  <script src="<?php echo constant('URL') . 'public/js/register.js' ?>"></script>
</body>

</html>