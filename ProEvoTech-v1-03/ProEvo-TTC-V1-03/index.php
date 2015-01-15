<?php
    session_start();
    include('acceso_Db/acceso_db.php');
?>
<!doctype html>
<html lang="en">
<head>
    <link href="estilos.css" rel="stylesheet" />
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php
	    if(isset($_SESSION['usuario_nombre'])) {
	?>
	        Bienvenido: <a href="perfil.php?id=<?=$_SESSION['usuario_id']?>"><strong><?=$_SESSION['usuario_nombre']?></strong></a><br />
	        <a href="logout.php">Cerrar Sesión</a>
	<?php
	    }else {
	?>
			<a href="registro.php">Registrarse</a> | <a href="acceso.php">Ingresar</a>
	<?php
	    }
	?> 
</body>
</html>