<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <?php require "../Util/base_tienda.php" ?>
    <?php require './producto.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./Styles/style.css">
</head>
<body>
    <?php
    # Creamos la sesión según el usuario que esté usando la página
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
    }
    ?>
    <?php
    # Preparamos el botón de añadir a la cesta para que según la cantidad que se seleccione, se añada a la cesta ese número de productos
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
                $sql = "select * from productoscestas where idProducto = '$id_producto' and idCesta = '$idCesta'";
                if ($conexion->query($sql)->num_rows == 0) {
                    $sql = "insert into productoscestas values ('$id_producto', '$idCesta', '$cantidad_seleccionada')";
                    $conexion->query($sql);
                } else {
                    $sql = "update productoscestas set cantidad = (cantidad + '$cantidad_seleccionada') where idProducto = '$id_producto' and idCesta = '$idCesta'";
                    $conexion->query($sql);
                }
                $sql = "select precio from productos where idProducto = '$id_producto'";
                $precio = $conexion->query($sql)->fetch_assoc()["precio"];
                $sql = "update cestas set precioTotal = (precioTotal + ('$precio' * '$cantidad_seleccionada')) where idCesta = '$idCesta'";
                $conexion->query($sql);
            }
        }
    }
    ?>
    <!-- Creamos nuestro nav -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark mb-3" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand mt-1" href="./principal.php"><img src="./Images/Jaimes_Retro.png" width="150px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./principal.php">Ver Stock</a>
                    </li>
                    <li>
                        <a class="nav-link active" aria-current="page" href="./cesta.php">Ver el carrito</a>
                    </li>
                    <?php
                    if ($_SESSION["rol"] == 'admin') {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="./productos.php">Añadir productos</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li>
                        <a class="nav-link active">Bienvenid@ <?php echo $usuario ?></a>
                    </li>
                </ul>
                <?php
                # Si el usuario es invitado, le mostramos el botón de iniciar sesión
                if ($usuario == "invitado") {
                ?>
                    <a class="btn btn-secondary" href="iniciarsesion.php">Iniciar Sesion</a>
                <?php
                } else {
                ?>
                    <!-- Si el usuario es cliente o Admin, le mostramos el botón de cerrar sesión -->
                    <a class="btn btn-secondary" aria-current="page" href="cerrarsesion.php">Cerrar Sesión</a>
                <?php
                }
                ?>
            </div>
        </div>
    </nav>
    <!-- Creamos el contenedor con nuestro logo -->
    <div class="container w-25 bienvenida">
        <div><img src="./Images/Jaimes_Retro.png"></div>
    </div>
    <!-- Creamos la tabla de los productos -->
    <div class="container">
        <table id="tabla" class="table table-dark table-striped table-hover">
            <thead class="table table-light">
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
                # Utilizamos el objeto producto para mostrar los productos de la base de datos
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
                # Insertamos los productos en la tabla
                foreach ($productos as $producto) {
                    echo "<tr>";
                    echo "<td>" . $producto->idProducto . "</td>";
                    echo "<td>" . $producto->nombreProducto . "</td>";
                    echo "<td>" . $producto->precio . "€</td>";
                    echo "<td>" . $producto->descripcion . "</td>";
                    echo "<td>" . $producto->cantidad . "</td>";
                ?>
                    <td>
                        <img class="fotoTabla" witdh="50" height="100" src="<?php echo $producto->imagen ?>">
                    </td>
                    <td>
                        <!-- Creamos el formulario para añadir productos a la cesta -->
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
    <footer class="bg-body-tertiary text-center text-lg-start">
        <div class="text-center p-3 mifooter mt-4" style="background-color: rgba(0, 0, 0, 0.05);">
            Jaime's Retro © 2023
        </div>
    </footer>
</body>
</html>
