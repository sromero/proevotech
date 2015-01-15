 <?php
    $host_db = 10.30.51.51"; // Host de la BD
    $usuario_db = "proevo"; // Usuario de la BD
    $clave_db = "apisql"; // Contraseña de la BD
    $nombre_db = "proevotechtransac"; // Nombre de la BD
    
    //conectamos y seleccionamos db
    mysql_connect($host_db, $usuario_db, $clave_db);
    mysql_select_db($nombre_db);
?> 