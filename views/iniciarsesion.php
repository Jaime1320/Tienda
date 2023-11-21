<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./Styles/inicio.css">
    <?php require "../Util/base_tienda.php" ?>
</head>

<body>
    <?php
    #Buscamos en la base de datos si existe el usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $conexion->query($sql);
    }

    ?>

    <!-- Cremao el formulario de inicio de sesion-->
    <div class="container">
        <h1>Iniciar sesión</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="formu" class="form-label">Usuario</label>
                <input class="form-control" type="text" name="usuario">
            </div>
            <div class="mb-4">
                <label class="formu" class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contrasena">
            </div>
            <input class="btn btn-success" type="submit" value="Iniciar sesion">
            <div class="mt-4">
                No tienes cuenta? <a href="./registro.php">Registrate</a>
            </div>
        </form>
        <?php
        if (isset($resultado)) {
            if ($resultado->num_rows == 0) {
        ?>
                <div class="alert alert-danger mt-2" role="alert">
                    No existe el Usuario
                </div>
                <?php
                #Si existe el usuario, comprobamos que la contraseña sea correcta
            } else {
                while ($fila = $resultado->fetch_assoc()) {
                    $contrasena_cifrada = $fila["contrasena"];
                    $rol = $fila["rol"];
                }
                $acceso_valido = password_verify($contrasena, $contrasena_cifrada);
                #Si la contraseña es correcta, iniciamos sesion
                if ($acceso_valido) {
                    echo "Bienvenido $usuario";
                    session_start();
                    $_SESSION["usuario"] = $usuario;
                    $_SESSION["rol"] = $rol;
                    header("Location: principal.php");
                } else {
                ?>
                    <div class="alert alert-danger mt-2" role="alert">
                        Usuario o contraseña incorrectos
                    </div>
        <?php
                }
            }
        }

        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>