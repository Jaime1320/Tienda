<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <?php require '../Util/base_tienda.php' ?>
    <link rel="stylesheet" href="./Styles/style.css">
</head>

<body>
    <?php
    #Creamos la sesion segun que usuario este usando la pagina
    session_start();
    if (isset($_SESSION["usuario"]) && isset($_SESSION["rol"])) {
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
    }
    if ($rol != "admin") {
    ?>
        <!--Comprobamos que nadie que no sea administrador pueda acceder a esta pagina-->
        <div class="container">
            <div class="alert alert-danger mt-4" role="alert">No has iniciado sesion como administrador</div>
            <button type="button" class="btn btn-warning"><a class="nav-link active" href="iniciarsesion.php" tabindex="-1">Volver a inicio de sesión</a></button>
        </div>
    <?php
    } else {
    ?>
        <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
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
                        <li class="nav-item">
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
                    </ul>
                    <a class="btn btn-secondary" aria-current="page" href="cerrarsesion.php">Cerrar Sesión</a>
                </div>
            </div>
        </nav>
        <?php

        function depurar($entrada)
        {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            return $salida;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $temp_nombreProducto = depurar($_POST["nombreProducto"]);
            $temp_precio = depurar($_POST["precio"]);
            $temp_descripcion = depurar($_POST["descripcion"]);
            $temp_cantidad = depurar($_POST["cantidad"]);

            //$_FILES["nombreCampo"]["queQueremosCoger"] -> TYPE, NAME, SIZE, TMP_NAME
            $nombre_imagen = $_FILES["imagen"]["name"];
            $tipo_imagen = $_FILES["imagen"]["type"];
            $tamano_imagen = $_FILES["imagen"]["size"];
            $ruta_temporal = $_FILES["imagen"]["tmp_name"];
            //echo $nombre_imagen . " " . $tipo_imagen . " " . $tamano_imagen . " " . $ruta_temporal;

            #   Validación nombreProducto
            if (strlen($temp_nombreProducto) == 0) {
                $err_nombreProducto = "El nombre es obligatorio";
            } else {
                $patron = "/^[a-zA-Z0-9 ]{1,40}$/";
                if (!preg_match($patron, $temp_nombreProducto)) {
                    $err_nombreProducto = "El nombre tiene que tener entre 1 y 40 caracteres";
                } else {
                    $nombreProducto = $temp_nombreProducto;
                }
            }

            #   Validación precio

            if (strlen($temp_precio) == 0) {
                $err_precio = "El precio es obligatorio";
            } elseif (!is_numeric($temp_precio)) {
                $err_precio = "El precio debe ser un número";
            } elseif ($temp_precio < 0) {
                $err_precio = "El precio no puede ser negativo";
            } elseif ($temp_precio > 99999.99) {
                $err_precio = "El precio no puede ser mayor de 99999.99";
            } else {
                $precio = $temp_precio;
            }

            #  Validación descripcion
            if (strlen($temp_descripcion) == 0) {
                $err_descripcion = "La descripcion es obligatoria";
            } else {
                if (strlen($temp_descripcion) > 255) {
                    $err_descripcion = "La descripcion no puede tener mas de 255 caracteres";
                } else {
                    $descripcion = $temp_descripcion;
                }
            }
            #  Validación cantidad
            if (strlen($temp_cantidad) == 0) {
                $err_cantidad = "La cantidad es obligatoria";
            } else {
                $patron = "/^[0-9]{1,10}$/";
                if (!preg_match($patron, $temp_cantidad)) {
                    $err_cantidad = "La cantidad no puede ser un numero decimal";
                } else {
                    $cantidad = $temp_cantidad;
                }
            }
            #   Validación imagen
            if ($tamano_imagen > 10000000) {
                $err_imagen = "La imagen no puede pesar mas de 1MB";
            } else {
                if ($tipo_imagen != "image/jpeg") {
                    $err_imagen = "Tiene que ser formato imagen";
                } else {
                    $ruta_final = "../views/Images/" . $nombre_imagen;
                    move_uploaded_file($ruta_temporal, $ruta_final);
                }
            }
        }

        ?>
        <div class="container cajaformu">
            <h1 class="insertar">Insertar producto</h1>
            <div>
                <form class="formulario" action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nombre producto: </label>
                        <input class="form-control" type="text" name="nombreProducto">
                        <?php if (isset($err_nombreProducto)) echo '<label class=text-white>' . $err_nombreProducto . '</label>' ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio: </label>
                        <input class="form-control" type="text" name="precio">
                        <?php if (isset($err_precio)) echo '<label class=text-white>' . $err_precio . '</label>' ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción: </label>
                        <input class="form-control" type="text" name="descripcion">
                        <?php if (isset($err_descripcion)) echo '<label class=text-white>' . $err_descripcion . '</label>' ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad: </label>
                        <input class="form-control" type="text" name="cantidad">
                        <?php if (isset($err_cantidad)) echo '<label class=text-white>' . $err_cantidad . '</label>' ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input class="form-control" type="file" name="imagen">
                        <?php if (isset($err_imagen)) echo '<label class=text-white>' . $err_imagen . '</label>' ?>
                    </div>
                    <button class="btn btn-warning" type="submit">Añadir producto</button>
                </form>
            </div>
        </div>
    <?php
        if (isset($nombreProducto) && isset($precio) && isset($descripcion) && isset($cantidad) && isset($ruta_final)) {
            $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad , imagen)
        VALUES ('$nombreProducto',
        '$precio',
        '$descripcion',
        '$cantidad',
        '$ruta_final')";
            $conexion->query($sql);
            echo "<div class='container text-white'><h3>Producto insertado con éxito</h3></div>";
        }
    }

    ?>
    <footer class="bg-body-tertiary text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3 mifooter mt-4" style="background-color: rgba(0, 0, 0, 0.05);">
            Jaime's Retro © 2023
        </div>
        <!-- Copyright -->
    </footer>
</body>
</html>