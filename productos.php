<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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


        #   Validación nombreProducto
        if (strlen($temp_nombreProducto) == 0) {
            $err_nombreProducto = "El nombre es obligatorio";
        } else {
            $patron = "/^[a-zA-Z0-9]{1,40}$/";
            if (!preg_match($patron, $temp_nombreProducto)) {
                $err_nombreProducto = "El nombre tiene que tener entre 1 y 40 caracteres";
            } else {
                $nombreProducto = $temp_nombreProducto;
                echo $nombreProducto;
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
                echo $precio;
            }
        }

        #  Validación descripcion
        if (strlen($temp_descripcion) == 0) {
            $err_descripcion = "La descripcion es obligatoria";
        } else {
            $patron = "/^[a-zA-Z0-9]{1,40}$/";
            if (!preg_match($patron, $temp_descripcion)) {
                $err_descripcion = "La descripcion tiene que tener entre 1 y 40 caracteres";
            } else {
                $descripcion = $temp_descripcion;
                echo $descripcion;
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
                echo $cantidad;
            }
        }
    }

    ?>
    <div class="container">
        <h1>Insertar producto</h1>
        <div>
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label">Nombre producto: </label>
                    <input class="form-control" type="text" name="nombreProducto">
                    <?php if (isset ($err_nombreProducto)) echo '<label class=text-danger>'.$err_nombreProducto. '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio: </label>
                    <input class="form-control" type="float" name="precio">
                    <?php if (isset($err_precio)) echo '<label class=text-danger>' . $err_precio . '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción: </label>
                    <input class="form-control" type="text" name="descripcion">
                    <?php if (isset ($err_descripcion)) echo '<label class=text-danger>'.$err_descripcion. '</label>' ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad: </label>
                    <input class="form-control" type="int" name="cantidad">
                    <?php if (isset ($err_cantidad)) echo '<label class=text-danger>'.$err_cantidad. '</label>' ?>
                </div>
                <button class="btn btn-primary" type="submit">Enviar</button>
            </form>
        </div>
    </div>
    <?php
    if (isset($nombreProducto) && isset($precio) && isset($descripcion) && isset($cantidad)) {
        $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad) VALUES ('$nombreProducto', '$precio', '$descripcion', '$cantidad')";
        $conexion->query($sql);
    }
    ?>
</body>

</html>