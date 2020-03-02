<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acceso al mapa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
            font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <script src="login.js"></script>
</head>
<body>

    <form class="form-signin" action="../">
        <img class="mb-4" src="../images/escudo-municipalidad.png" alt="" width="310" height="124">
        <h1 class="h4 mb-3 font-weight-normal">Mantenimiento de Calles</h1>
        
        <label for="inputUsername" class="sr-only">Usuario</label>
        <input type="text" id="inputUsername" class="form-control" placeholder="Usuario" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
        
        <button class="btn btn-lg btn-primary btn-block" id="btnValidar" type="submit">Ingresar</button>
        <p class="mt-5 mb-3 text-muted">&copy; Dir. Gra. SIG - 2019</p>

        <input type="hidden" name="token" value="aGwnmqlSRT454_;ADFewer">
    </form>
    
<script>
    document.getElementById('btnValidar').addEventListener('click', validar, false);
</script>
</body>
</html>