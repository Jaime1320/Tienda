<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <?php require "../Util/base_tienda.php" ?>
    <?php require './producto.php'; ?>
    <link rel="stylesheet" href="./Styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        //header("Location: iniciarsesion.php");
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
    }
    ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_producto = $_POST["idProducto"];
        $cantidad_seleccionada = $_POST["cantidad"];
        if ($cantidad_seleccionada != "") {
            $sql = "select cantidad from productos where idProducto = '$id_producto'";
            $cantidadProducto = $conexion->query($sql)->fetch_assoc()["cantidad"];
            if ($cantidadProducto != "0") {
                $sql = "select idCesta from cestas where usuario = '$usuario'";
                $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
                // restar
                $sql = "update productos set cantidad = (cantidad - '$cantidad_seleccionada') where idProducto = '$id_producto'";
                $conexion->query($sql);
                $yaEsta = "select * from productoscestas where idProducto = '$id_producto'";
                if ($conexion->query($yaEsta)->num_rows == 0) {
                    $sql = "insert into productoscestas values ('$id_producto', '$idCesta', '$cantidad_seleccionada')";
                    $conexion->query($sql);
                } else {
                    $sql = "select cantidad from productoscestas where idProducto = '$id_producto'";
                    $cantidadCesta = $conexion->query($sql)->fetch_assoc()["cantidad"];
                    $sql = "update productoscestas set cantidad = (cantidad + '$cantidadCesta') where idProducto = '$id_producto'";
                    $conexion->query($sql);
                }
            }
        }
    }
    ?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark mb-3" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./principal.php"><img src="./Images/logofinal.PNG" width="150px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./principal.php">Ver Stock</a>
                    </li>
                    <?php
                    if ($_SESSION["rol"] == 'admin') {
                    ?>
                        <li class="nav-item">

                            <a class="losa" class="nav-link active" aria-current="page" href="./productos.php">Productos</a>

                            <a class="nav-link active" aria-current="page" href="./productos.php">Añadir productos</a>

                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                if ($usuario == "invitado") {
                ?>
                    <a class="btn btn-secondary" href="iniciarsesion.php">Iniciar Sesion</a>
                <?php
                } else {
                ?>
                    <a class="btn btn-secondary" aria-current="page" href="cerrarsesion.php">Cerrar Sesión</a>
                <?php
                }
                ?>
            </div>
        </div>
    </nav>


    <div id="bienvenida" class="container">
        <h1 class="text-white">La tiendecilla de Jaime</h1>
        <h2 class="text-white">Bienvenid@ <?php echo $usuario ?></h2>
    </div>
    <?php
    if (isset($mensajeCesta)) {
        echo $mensajeCesta;
    }

    ?>
    <div class="container">
        <table id="tabla" class="table table-striped table-hover">
            <thead class="table table-dark">
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Añadir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM productos";
                $resultado = $conexion->query($sql);
                $productos = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $producto_Nuevo = new Producto(
                        $fila['idProducto'],
                        $fila['nombreProducto'],
                        $fila['precio'],
                        $fila['descripcion'],
                        $fila['cantidad'],
                        $fila['imagen']
                    );
                    array_push($productos, $producto_Nuevo);
                }
                ?>
                <?php
                foreach ($productos as $producto) {
                    echo "<tr>";
                    echo "<td>" . $producto->idProducto . "</td>";
                    echo "<td>" . $producto->nombreProducto . "</td>";
                    echo "<td>" . $producto->precio . "</td>";
                    echo "<td>" . $producto->descripcion . "</td>";
                    echo "<td>" . $producto->cantidad . "</td>";

                ?>
                    <td>
                        <img witdh="50" height="100" src="<?php echo $producto->imagen ?>">
                    </td>
                    <td>

                        <form action="" method="post">
                            <?php if (($usuario != "invitado")) { ?>
                                <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                <label for="cantidad">Cantidad:</label>
                                <select name="cantidad">
                                    <?php
                                    $sql = "SELECT cantidad FROM productos where idProducto = '$producto->idProducto'";
                                    $cantidadActual = $conexion->query($sql)->fetch_assoc()["cantidad"];
                                    $maxCantidad = min(5, $cantidadActual);
                                    for ($i = 1; $i <= $maxCantidad; $i++) {
                                    ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <?php
                                if ($cantidadActual > 0) {
                                ?>
                                    <input class="btn btn-warning" type="submit" value="Añadir">
                                <?php
                                } else {
                                ?>
                                    <input class="btn btn-warning" type="submit" value="Añadir" disabled>
                                <?php
                                }
                            } else { ?>
                                <input class="btn btn-warning" type="submit" value="Añadir" disabled>
                            <?php } ?>
                        </form>
                    </td>
                <?php
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>