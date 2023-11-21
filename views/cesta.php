<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <?php require '../Util/base_tienda.php' ?>
    <?php require './productoCesta.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./Styles/style.css">
</head>

<body>
    <?php
    #Creamos la sesion segun quien este usando la pagina
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
    if ($usuario != "invitado") {

        $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen FROM productoscestas pc JOIN productos p ON pc.idProducto = p.idProducto WHERE pc.idCesta = (SELECT idCesta FROM cestas WHERE usuario = '$usuario')";
        $resultado = $conexion->query($sql);
        $sql = "select precioTotal from cestas where usuario = '$usuario'";
        $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];
        $productosCesta = [];
        while ($fila = $resultado->fetch_assoc()) {
            $nuevoProducto = new productoCesta(
                $fila["idProducto"],
                $fila["nombreProducto"],
                $fila["precio"],
                $fila["descripcion"],
                $fila["cantidad"],
                $fila["imagen"]
            );
            array_push($productosCesta, $nuevoProducto);
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
        <!--Creamos el contenedor de nuestro logo con la bienvenida-->
        <div class="container w-25 bienvenida">
            <div><img src="./Images/Jaimes_Retro.png"></div>
        </div>
        <!--Creamos nuestra tabla-->
        <div class="container">
            <table id="tabla" class="table table-striped table-hover">
                <thead class="table table-dark">
                    <tr>
                        <td>Nombre</td>
                        <td>Precio</td>
                        <td>Descripcion</td>
                        <td>Cantidad</td>
                        <td>Imagen</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($productosCesta as $producto) {
                        echo "<tr>";
                        echo "<td>" . $producto->nombreProducto . "</td>";
                        echo "<td>" . $producto->precio . "€</td>";
                        echo "<td>" . $producto->descripcion . "</td>";
                        echo "<td>" . $producto->cantidad . "</td>";

                    ?>
                        <td>
                            <img class="fotoTabla" witdh="50" height="100" src="<?php echo $producto->imagen ?>">
                        </td>
                    <?php
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="text-white bienvenida1">
                <h3>El precio total de tu carrito es de: <?php echo $precioTotal ?>€</h3>
            </div>
        <?php
        #Sino es admin o cliente lo mandamos a iniciar sesion
    } else {
        header("Location: iniciarsesion.php");
    }
        ?>
        </div>
        <footer class="bg-body-tertiary text-center text-lg-start">
            <!-- Copyright -->
            <div class="text-center p-3 mifooter mt-4" style="background-color: rgba(0, 0, 0, 0.05);">
                Jaime's Retro © 2023
            </div>
            <!-- Copyright -->
        </footer>
</body>

</html>