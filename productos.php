<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <?php require '../Tienda/BaseDatos/base_tienda.php' ?>
</head>

<body>
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
            $patron = "/^[a-zA-Z0-9]{1,40}$/";
            if (!preg_match($patron, $temp_nombreProducto)) {
                $err_nombreProducto = "El nombre tiene que tener entre 1 y 40 caracteres";
            } else {
                $nombreProducto = $temp_nombreProducto;
            }
        }

        #   Validación precio
        if (strlen($temp_precio) == 0) {
            $err_precio = "El precio es obligatorio";
        } else {
            $patron = "/^[0-9]{1,10}$/";
            if (!preg_match($patron, $temp_precio)) {
                $err_precio = "El precio tiene que tener entre 1 y 10 caracteres";
            } else {
                $precio = $temp_precio;
            }
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
                $err_cantidad = "La cantidad tiene que tener entre 1 y 10 caracteres";
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
                $ruta_final = "imagenes/" . $nombre_imagen;
                move_uploaded_file($ruta_temporal, $ruta_final);
            }
        }
    }

    ?>
    <div class="container">
        <h1>Insertar producto</h1>
        <div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nombre producto: </label>
                    <input class="form-control" type="text" name="nombreProducto">
                    <?php if (isset($err_nombreProducto)) echo '<label class=text-danger>' . $err_nombreProducto . '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio: </label>
                    <input class="form-control" type="text" name="precio">
                    <?php if (isset($err_precio)) echo '<label class=text-danger>' . $err_precio . '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción: </label>
                    <input class="form-control" type="text" name="descripcion">
                    <?php if (isset($err_descripcion)) echo '<label class=text-danger>' . $err_descripcion . '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad: </label>
                    <input class="form-control" type="text" name="cantidad">
                    <?php if (isset($err_cantidad)) echo '<label class=text-danger>' . $err_cantidad . '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <input class="form-control" type="file" name="imagen">
                    <?php if (isset($err_imagen)) echo '<label class=text-danger>' . $err_imagen . '</label>' ?>
                </div>
                <button class="btn btn-primary" type="submit">Enviar</button>
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
        echo "<div class='container'><h3>Producto insertado con éxito</h3></div>";
    }
    ?>
</body>

</html>