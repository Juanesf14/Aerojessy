<?php

require('Connection.php');

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'];

    // Cambiado 'ususario' a 'usuario'
    $query = "SELECT * FROM usuario WHERE ID_Usuario = $userId";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Accede a los campos del usuario
        $username = $row['Nombre_Usuario'];
        $email = $row['Correo_Electronico'];
        $phone = $row['Numero_Telefono'];
        $role = $row['Rol'];
    } else {
        die("Error en la consulta SQL: " . mysqli_error($con));
    }
} else {
    // Manejo si no hay sesión iniciada
    header("Location: Login.php");
    exit();
}
?>

<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        header {
            background-color: #02122c;
            top: 0;
            left: 0;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <?php require('Header.php'); ?>
    <div class="container">
        <h2>Detalles del Usuario</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <p>ID de Usuario: <?php echo $userId; ?></p>
                <p>Nombre de Usuario: <?php echo $username; ?></p>
                <p>Correo Electrónico: <?php echo $email; ?></p>
                <p>Número de Teléfono: <?php echo $phone; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
