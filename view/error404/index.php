<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo constant('URL') . 'public/css/default.css' ?>">
  <link rel="stylesheet" href="<?php echo constant('URL') . 'public/css/error404.css' ?>">
  <title>PokeApi</title>
</head>

<body>
  <div id="main">
    <div class="content">
      <h1>Esta página no existe</h1>
      <p>Comprueba la URL o vuelve a la página de inicio.</p>
      <br>
      <button class="button-confirm" onclick="window.location.href='<?php echo constant('URL') ?>'">Ir a la página de inicio</button>
    </div>
  </div>
</body>

</html>