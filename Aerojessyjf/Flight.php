<?php
include_once('Connection.php');

if (!isset($_SESSION)) session_start();
$allowed = false;
$result;

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['modify'])) {
    $allowed = true;
}

$origin = !empty($_GET['origin']) ? $_GET['origin'] : $_SESSION['origin'];
$destination = !empty($_GET['destination']) ? $_GET['destination'] : $_SESSION['destination'];
$depart = !empty($_GET['depart']) ? $_GET['depart'] : $_SESSION['depart'];

$_SESSION['origin'] = $origin;
$_SESSION['destination'] = $destination;
$_SESSION['depart'] = $depart;

$query = "SELECT vuelo.ID_Vuelo, vuelo.Fecha_De_Salida, vuelo.Hora_Salida, vuelo.lugar_Origen, vuelo.lugar_Destino, vuelo.Precios_asiento, vuelo.Numero_Asienti, Company.name,
TIMEDIFF(vuelo.Hora_Llegada, vuelo.Hora_Salida) AS duration,
ADDTIME(vuelo.Hora_Salida, TIMEDIFF(vuelo.Hora_Llegada, vuelo.Hora_Salida)) AS arrival_time
FROM vuelo 
INNER JOIN Company ON vuelo.company_id = Company.ID_Company 
WHERE vuelo.lugar_Origen = '" . $origin . "' AND vuelo.lugar_Destino = '" . $destination . "';";

$result = mysqli_query($con, $query);

if (isset($_GET['book'])) {
    if (!$_SESSION['user']) {
        header("Location: Login.php?flightId=" . $_GET['flightId']);
    } else {
        echo $_SESSION['user'];
        unset($_SESSION['count']);
        unset($_SESSION['pas']);
        header("Location: Booking.php?flightId=" . $_GET['flightId']);
    }
}
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="header.css" />
    <link rel="stylesheet" type="text/css" href="flight.css" />
    <style>
        header {
            background-color: #02122c;
            top: 0;
            left: 0;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <?php include('Header.php'); ?>
        <div class="search-details-container">
            <div class="field-container">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
                    <div class="source-field">
                        <div>From</div>
                        <div>
                            <input type="text" value="<?php echo $_SESSION['origin'] ?>" name="origin" id="origin" <?php if (isset($allowed) && !$allowed) echo "readonly"; ?>>
                        </div>
                    </div>
                    <div class="destination-field">
                        <div>To</div>
                        <div>
                            <input type="text" value="<?php echo $_SESSION['destination'] ?>" name="destination" id="destination" <?php if (isset($allowed) && !$allowed) echo "readonly"; ?>>
                        </div>
                    </div>

                    <div class="date-container">
                        <div>Date</div>
                        <div>
                            <input type="date" value="<?php echo $_SESSION['depart'] ?>" name="depart" id="depart" <?php if (isset($allowed) && !$allowed) echo "readonly"; ?>>
                        </div>
                    </div>
                    <div class="modify-container">
                        <?php
                        if (isset($allowed) && !$allowed)
                            echo '<button type="submit" name="modify">Modify</button>';
                        else
                            echo '<button type="submit" name="search">Search</button>'
                        ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="search-display-container">
            <?php
            if (isset($result) && mysqli_num_rows($result) > 0) {
                echo "<h3>Your Flights<br></h3><hr>";
                while ($row = mysqli_fetch_assoc($result)) {

                    echo '<div class="flight-container"><div class="flight-details field-container ">
                            <div>' . $row['name'] . '</div>
                            <div class="departure_detail">
                                <div>' . $row['Hora_Salida'] . '</div>               
                                <div class="city">' . $row['lugar_Origen'] . '</div>
                            </div>
                            <div>' . $row['duration'] . '</div>
                            <div class="departure_detail">
                                <div>' . $row['arrival_time'] . '</div>
                                <div class="city">' . $row['lugar_Destino'] . '</div>
                            </div>
                        </div>';

                    echo '<div class="flight-booking-details field-container">
                            <div style="font-size:14
                            <div>' . $row['Numero_Asienti'] . '</div>
                            <div class="price">₹' . $row['Precios_asiento'] . '</div>
                            <form action="' . $_SERVER['PHP_SELF'] . '" method="GET">
                                <input type="text" name="flightId" hidden value="' . $row['ID_Vuelo'] . '">
                                <button type="submit" class="book" name="book">BOOK</button>
                            </form>
                        </div></div>';
                }
            } else {
                echo "No Available Flights";
            }
            ?>
        </div>
        <?php include('Footer.php'); ?>
    </div>
</body>

</html>
