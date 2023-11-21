<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Styles/inicio.css">
    <?php require "../Util/base_tienda.php" ?>
</head>
<body>
    <?php
    # Buscamos en la base de datos si existe el usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $conexion->query($sql);
    }
    ?>
    <!-- Formulario de inicio de sesión -->
    <div class="container">
        <h1>Iniciar sesión</h1>
        <form action="" method="post">
            <div class="mb-3">
                <!-- Creamos el campo para usuario -->
                <label class="formu" class="form-label">Usuario</label>
                <input class="form-control" type="text" name="usuario">
            </div>
            <div class="mb-4">
                <!-- Creamos el campo de contraseña -->
                <label class="formu" class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contrasena">
            </div>
            <!-- Creamos el botón para enviar el formulario -->
            <input class="btn btn-success" type="submit" value="Iniciar sesión">
            <div class="mt-4">
                <!-- Ponemos un enlace para registrarse sino esta registrado -->
                No tienes cuenta? <a href="./registro.php">Registrate</a>
            </div>
        </form>
        <?php
        # Comprobamos si existe la variable resultado
        if (isset($resultado)) {
            # Si no hay resultados, el usuario no existe
            if ($resultado->num_rows == 0) {
        ?>
                <div class="alert alert-danger mt-2" role="alert">
                    No existe el Usuario
                </div>
                <?php
            } else {
                # Si hay resultados, comprobamos la contraseña
                while ($fila = $resultado->fetch_assoc()) {
                    $contrasena_cifrada = $fila["contrasena"];
                    $rol = $fila["rol"];
                }

                # Verificamos si la contraseña ingresada coincide con la almacenada
                $acceso_valido = password_verify($contrasena, $contrasena_cifrada);

                # Si la contraseña es correcta iniciamos sesión
                if ($acceso_valido) {
                    echo "Bienvenido $usuario";
                    session_start();
                    $_SESSION["usuario"] = $usuario;
                    $_SESSION["rol"] = $rol;
                    header("Location: principal.php");
                } else {
                ?>
                    <!-- Creamos un mensaje de error si la contraseña es incorrecta -->
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
