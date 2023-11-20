<?php
    #Cerramos la sesión
    session_start();
    session_destroy();
    header('location:iniciarsesion.php');
?>