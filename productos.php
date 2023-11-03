<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Insertar producto</h1>
        <div>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre producto: </label>
                    <input class="form-control" type="text" name="nombreProducto">
                </div>
                <div class="mb-3">
                    <label class="form-label">precio: </label>
                    <input class="form-control" type="float" name="precio">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción: </label>
                    <input class="form-control" type="text" name="descripcion">
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad: </label>
                    <input class="form-control" type="int" name="cantidad">
                </div>
                <button class="btn btn-primary" type="submit">Enviar</button>
            </form>
        </div>
    </div>

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
    }

    if (isset($nombreProducto) && isset($precio) && isset($descripcion) && isset($cantidad)) {
        $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad) VALUES ('$nombreProducto', '$precio', '$descripcion', '$cantidad')";
        $conexion->query($sql);
    }
    ?>
</body>

</html>