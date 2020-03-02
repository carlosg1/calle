<?php
    session_name('mapa_gis');
    session_start();

    unset($_SESSION['validado'], $_SESSION['usuario']);

    session_destroy();

    header('location: ../../.');
