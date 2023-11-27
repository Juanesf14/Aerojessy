<?php
require('Connection.php');

$reservationId = null;  // Declarar $reservationId con un valor predeterminado

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm'])) {
    // Insertar en la tabla Reserva.
    $insertReservaQuery = "INSERT INTO Reserva (Fecha_Reserva, Estado_Reserva, Numero_Asientos_Reservados, Precio_Total)
                            VALUES (NOW(), 'Confirmada', " . $_SESSION['count'] . ", " . ($_SESSION['count'] * $_SESSION['flight_details']['Precios_asiento']) . ")";
    if (!mysqli_query($con, $insertReservaQuery)) {
        echo "Error al insertar en la tabla Reserva: " . mysqli_error($con);
        exit;
    }

    // Obtener el ID de la reserva recién creada.
    $reservationId = mysqli_insert_id($con);
    echo "ID de Reserva: " . $reservationId;  // Mensaje de depuración

    // Insertar detalles de pasajeros en la tabla detalles_pasajeros.
    foreach ($_SESSION['pas'] as $passenger) {
        $name = $passenger[0];
        $age = $passenger[1];
        $insertPasajeroQuery = "INSERT INTO detalles_pasajeros (ID_Reserva, Nombre, Edad)
                                VALUES ('$reservationId', '$name', '$age')";
        if (!mysqli_query($con, $insertPasajeroQuery)) {
            echo "Error al insertar detalles de pasajeros: " . mysqli_error($con);
            exit;
        }
    }

    // Restablecer las variables de sesión después de confirmar la reserva.
    unset($_SESSION['pas']);
    unset($_SESSION['count']);
    unset($_SESSION['flight_details']);
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
        <h2>Reserva Confirmada</h2>
        <div class="panel panel-default">
            <div class="panel-heading">Detalles de la Reserva</div>
            <div class="panel-body">
                <p>Número de Reserva: <?php echo $reservationId; ?></p>
                <p>Número de Pasajeros: <?php echo $_SESSION['count']; ?></p>
                <p>Precio Total: <?php echo $_SESSION['count'] * $_SESSION['flight_details']['Precios_asiento']; ?></p>
                <!-- Agrega aquí más detalles de la reserva según tus necesidades -->
            </div>
        </div>

        <a href="Home.php" class="btn btn-primary">Salir</a>
    </div>
</body>

</html>
