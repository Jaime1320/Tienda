<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <link rel="stylesheet" href="style.css">
    <?php require '../Util/base_tienda.php' ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario1 = $_SESSION["usuario"];
        
        $sql2 = "SELECT idCesta from cestas WHERE usuario='" . $usuario1 . "'";
        $resultado2 = $conexion->query($sql2);
        //fecth assoc here
        while ($fila = $resultado2->fetch_assoc()) {
             $idCesta= $fila['idCesta'] ;
        }
        echo $idCesta;
        $sql = "SELECT p.nombreProducto AS nombre, p.precio as precio, p.imagen as imagen, c.cantidad as cantidad 
        from productos p join productoscestas c on p.idProducto = c.idProducto 
        where c.idCesta = $idCesta";
        $resultado = $conexion->query($sql);
        while ($fila2 = $resultado->fetch_assoc()) {
             echo $fila2["nombreProducto"];
             
        }
    }

    ?>
</body>

</html>
=======
>>>>>>> 86af586d32a516eb11fdcd264e3bb8612956211a
