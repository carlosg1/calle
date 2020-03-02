<?php
$username = $_GET['usuario'];
$password = $_GET['password'];

$permitido = 'NO';

if($username=='carlosg' && $password=='147') $permitido = 'SI';
if($username=='dario' && $password=='147') $permitido = 'SI';
if($username=='romina' && $password=='147') $permitido = 'SI';
if($username=='mapagis' && $password=='a45r') $permitido = 'SI';
if($username=='visor' && $password=='k537c') $permitido = 'SI';
if($username=='ingreso' && $password=='t619w') $permitido = 'SI';

if($permitido=='SI'){

    session_name('mapa_gis');
    session_start();

    $_SESSION['validado'] = 'SI';
    $_SESSION['usuario'] = $username;

}

echo $permitido;

?>
