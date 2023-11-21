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
    # Inicia la sesión y obtiene información del usuario
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        # Si no hay sesión, establece un usuario invitado
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
    }

    if ($usuario != "invitado") {

        # Cogemos información de la cesta del usuario desde la base de datos
        $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen FROM productoscestas pc JOIN productos p ON pc.idProducto = p.idProducto WHERE pc.idCesta = (SELECT idCesta FROM cestas WHERE usuario = '$usuario')";
        $resultado = $conexion->query($sql);

        # Cogemos el precio total de la cesta
        $sql = "select precioTotal from cestas where usuario = '$usuario'";
        $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];
        
        $productosCesta = [];
        while ($fila = $resultado->fetch_assoc()) {
            # Crea el objeto productoCesta y los agrega al array
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
        <!-- Creamos la barra de navegación -->
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
                    #Si el usuario es invitado, muestra el botón de iniciar sesión
                    if ($usuario == "invitado") {
                    ?>
                        <a class="btn btn-secondary" href="iniciarsesion.php">Iniciar Sesion</a>
                    <?php
                    } else {
                    #Si el usuario es admin o cliente, muestra el botón de cerrar sesión
                    ?>
                        <a class="btn btn-secondary" aria-current="page" href="cerrarsesion.php">Cerrar Sesión</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </nav>
        <!-- Creamos el contenedor de bienvenida con nuestro logo -->
        <div class="container w-25 bienvenida">
            <div><img src="./Images/Jaimes_Retro.png"></div>
        </div>
        <!-- Creamos la tabla que muestra los productos en la cesta -->
        <div class="container">
            <table id="tabla" class="table table-dark table-striped table-hover">
                <thead class="table table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($productosCesta as $producto) {
                        # Muestra información de cada producto en la cesta en filas de la tabla
                        echo "<tr>";
                        echo "<td>" . $producto->nombreProducto . "</td>";
                        echo "<td>" . $producto->precio . "€</td>";
                        echo "<td>" . $producto->descripcion . "</td>";
                        echo "<td>" . $producto->cantidad . "</td>";
                        echo "<td><img class='fotoTabla' witdh='50' height='100' src='" . $producto->imagen . "'></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Muestra el precio total de la cesta y un formulario para tramitar el pedido -->
            <div class="text-white pb-3 pt-3 bienvenida1">
                <h3>El precio total de tu carrito es de: <?php echo $precioTotal ?>€</h3>
            </div>
        <?php
    } else {
        # Si el usuario no es admin o cliente, te lleva a la página de inicio de sesión
        header("Location: iniciarsesion.php");
    }
    ?>
    </div>
    <footer class="bg-body-tertiary text-center text-lg-start">
        <div class="text-center p-3 mifooter mt-4" style="background-color: rgba(0, 0, 0, 0.05);">
            Jaime's Retro © 2023
        </div>
    </footer>
</body>

</html>
