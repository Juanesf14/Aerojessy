<?php
// Incluye la conexión a la base de datos
require('Connection.php');

// Inicializa las variables de sesión o utiliza un valor predeterminado si no existen
$count = $_SESSION['count'] ?? 0;
$pas = $_SESSION['pas'] ?? array();

// Verifica si existe la información de vuelo en la sesión
if (isset($_SESSION['flight_details'])) {
    $result = $_SESSION['flight_details'];
} else {
    // Si no existe, realiza una consulta para obtener la información del vuelo
    $query = "SELECT lugar_Origen, lugar_Destino, Fecha_De_Salida, Hora_Salida, Numero_Asienti, Precios_asiento, ID_Vuelo FROM vuelo WHERE ID_Vuelo='" . $_GET['flightId'] . "';";
    $temp = mysqli_query($con, $query);

    // Si se encuentra el vuelo, guarda la información en la sesión
    if (isset($temp) && mysqli_num_rows($temp) > 0) {
        while ($row = mysqli_fetch_assoc($temp)) {
            $result = array(
                "lugar_Origen" => $row['lugar_Origen'],
                "lugar_Destino" => $row['lugar_Destino'],
                "Fecha_De_Salida" => $row['Fecha_De_Salida'],
                "Hora_Salida" => $row['Hora_Salida'],
                "Numero_Asienti" => $row['Numero_Asienti'],
                "Precios_asiento" => $row['Precios_asiento'],
                "ID_Vuelo" => $row['ID_Vuelo'],
            );
        }
        $_SESSION['flight_details'] = $result;
    }
}

// Elimina un pasajero si se envía una solicitud GET 'remove'
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['remove'])) {
    $rid = $_GET['removeId'];
    $pas = $_SESSION['pas'];
    for ($x = 0; $x < count($pas); $x++) {
        if ($pas[$x][3] == $rid) {
            array_splice($pas, $x, 1);
        }
    }
    $count = $count - 1;
}

// Agrega un pasajero si se envía una solicitud GET 'save'
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['save'])) {
    $name = $_GET['name'];
    $age = $_GET['age'];

    // Verifica que los campos de nombre y edad no estén vacíos
    if (strlen($name) && strlen($age)) {
        $new = array($name, $age, $count);
        $count = $count + 1;
        array_push($pas, $new);
    }
}

// Guarda las variables de sesión actualizadas
$_SESSION['pas'] = $pas;
$_SESSION['count'] = $count;

// Procesa la confirmación de la reserva si se envía una solicitud GET 'confirm'
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['confirm'])) {
    // Realiza la inserción en la tabla de Reserva
    $fechaReserva = date('Y-m-d');
    $estadoReserva = "Confirmada";
    $numAsientosReservados = $count;
    $precioTotal = $count * $result['Precios_asiento'];

    // Inserta la reserva en la base de datos
    $insertQuery = "INSERT INTO Reserva (Fecha_Reserva, Estado_Reserva, Numero_Asientos_Reservados, Precio_Total) VALUES ('$fechaReserva', '$estadoReserva', $numAsientosReservados, $precioTotal)";

    if (mysqli_query($con, $insertQuery)) {
        // Obtiene el ID de la última reserva insertada
        $idReserva = mysqli_insert_id($con);

        // Inserta detalles de pasajeros en otra tabla (asumiendo una tabla llamada Detalles_Pasajeros)
        foreach ($pas as $pasajero) {
            $nombre = $pasajero[0];
            $edad = $pasajero[1];

            $insertDetallesQuery = "INSERT INTO Detalles_Pasajeros (ID_Reserva, Nombre, Edad) VALUES ($idReserva, '$nombre', $edad)";
            mysqli_query($con, $insertDetallesQuery);
        }

        // Redirige a una página de confirmación o a la página de inicio
        header("Location: Confirmacion.php");
        exit();
    } else {
        echo "Error en la inserción de la reserva: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Reservation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="booking.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        header {
            background-color: #02122c;
            top: 0;
            left: 0;
        }
    </style>
</head>

<body>
    <?php require('Header.php'); ?>
    <div class="main-container">
        <div class="passenger-list">
            <button type="button" class="btn btn-lg" data-toggle="modal" data-target="#myModal">Add Passenger</button>
            <div class="show-passenger">
                <?php
                // Muestra la lista de pasajeros
                for ($x = 0; $x < count($pas); $x++) {
                    echo '
                        <div class="individual-pass">
                            <p>' . $pas[$x][0] . '</p>
                            <p>' . $pas[$x][1] . '</p>
                            <div class="room-detail">
                                <form>
                                    <input type="hidden" name="removeId" value="' . $pas[$x][2] . '">
                                    <button type="submit" class="remove" name="remove">Remove</button>
                                </form>  
                            </div>
                        </div>
                    ';
                }
                ?>
            </div>
        </div>
        <div class="bill-con">
            <div class="bill">
                <table>
                    <tr>
                        <td>Total Passenger</td>
                        <td style="padding-left:70px;"><?php echo $count; ?></td>
                    </tr>
                    <tr>
                        <td>Ticket Price</td>
                        <td style="padding-left:70px;"><?php echo $result['Precios_asiento']; ?></td>
                    </tr>
                   

            </div>
        </div>
        <div class="bill-con">
            <div class="bill">
                <table>
                    <tr>
                        <td>Total Passenger</td>
                        <td style="padding-left:70px;"><?php echo $count; ?></td>
                    </tr>
                    <tr>
                        <td>Ticket Price</td>
                        <td style="padding-left:70px;"><?php echo $result['Precios_asiento']; ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td style="padding-left:70px; font-weight:bold; font-size:20px;"><?php echo $count * $result['Precios_asiento']; ?></td>
                    </tr>
                </table>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
                <button type="submit" name="confirm" class="confirm">Confirm Ticket</button>
            </form>
        </div>
    </div>
    <?php require("footer.php"); ?>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Passenger Details</h4>
                </div>
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
                    <div class="modal-body">
                        <label>Name:</label><input type="text" class="name" name="name" pattern="[A-Za-z]{2,50}" title="Only alphabets"><br>
                        <label>Age:</label><input type="Number" name="age" class="age" min="0" max="110"><br>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" name="save">ADD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
