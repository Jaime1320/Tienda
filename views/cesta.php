
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <link rel="stylesheet" href="style.css">
    <?php require '../Util/base_tienda.php' ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php
    session_start();
    if ($usuario != "invitado") {
        $sql = "select * from productoscestas where idCesta "
    }
    ?>
</body>

</html>

