<?php
session_start();
include_once('Connection.php');

$originError = $destinationError = $departError = "";
$flag = false;

if (isset($_GET['search'])) {
    $origin = $_GET['origin'];
    $destination = $_GET['destination'];
    $depart = $_GET['depart'];

    // Additional validation to ensure the fields are not empty
    if (empty($origin) || empty($destination) || empty($depart)) {
        $flag = true;
        echo "All fields are required.";
    } else {
        $originError = validateCityName($origin, "FROM");
        $destinationError = validateCityName($destination, "TO");
        $departError = validateDate($depart);

        if (!$originError && !$destinationError && !$departError) {
            $_SESSION['origin'] = $origin;
            $_SESSION['destination'] = $destination;
            
			
			$_SESSION['depart'] = $depart;
			$query = "SELECT * FROM vuelo WHERE lugar_Origen='" . $origin . "' AND lugar_Destino='" . $destination . "' AND Fecha_De_Salida='" . $depart . "';";

            $result = mysqli_query($con, $query);

            if ($result) {
                $_SESSION['flights'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
                header("Location: Flight.php");
            } else {
                echo "Query failed: " . mysqli_error($con);
            }
        } else {
            $flag = true;
            echo "Please fix the errors.";
        }
    }
}

function validateCityName($city, $field)
{
    $error = "";
    if (empty($city) || strlen($city) == 0) {
        $error = "City name is required in " . $field . " field";
    } else {
        $city = test_input($city);
        if (!preg_match("/^[a-zA-Z]*$/", $city)) {
            $error = "Give Valid City Name In " . $field . " Field";
        }
    }
    return $error;
}

function validateDate($date)
{
    $error = "";
    if (empty($date)) {
        $error = "Choose Travel Date";
    } else {
        $date = test_input($date);
        $d = explode("-", $date);
        if (!checkdate($d[1], $d[2], $d[0])) {
            $error = "Enter Valid Date";
        }
    }
    return $error;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flights</title>
    <link rel="stylesheet" type="text/css" href="header.css">
    <link rel="stylesheet" type="text/css" href="search.css">

    <style type="text/css">
        .head {
            background-image: url(https://www.itsgettinghotinhere.org/wp-content/uploads/2018/03/150324_flights-hero-image_1330x742.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            background-blend-mode: multiply;
            font-family: verdana;
        }
    </style>
</head>

<body>
<div class="main_container">
    <div class="head">
        <?php include 'Header.php'; ?>

        <div class="flight-details-box">
            <div class="flight-form">
                <div class="error">
                    <?php if ($flag) echo $originError . $destinationError . $departError; ?>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
                    <div class="flight-detail">
                        <div class="origin inp">
                            <p>From</p>
                            <input type="text" value="<?php if (isset($origin)) echo $origin ?>" name="origin" id="flight-origin" placeholder="City">
                        </div>
                        <div class="destination inp">
                            <p>To</p>
                            <input type="text" value="<?php if (isset($destination)) echo $destination ?>" name="destination" id="flight-destination" placeholder="City">
                        </div>
                        <div class="depart inp">
                            <p>Departure Date</p>
                            <input type="date" value="<?php if (isset($depart)) echo $depart ?>" name="depart" id="depdate">
                        </div>
                    </div>
                    <div class="search-flight">
                        <button type="submit" name="search" class="search-flight-btn">
                            <span style="margin-top:3px;">Search flights</span>
                            <span style="line-height:1.5rem;display:inline-block;margin-top:0.1875rem;vertical-align:top"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="width:1.5rem;height:1.5rem" fill="white"><path d="M14.4 19.5l5.7-5.3c.4-.4.7-.9.8-1.5.1-.3.1-.5.1-.7s0-.4-.1-.6c-.1-.6-.4-1.1-.8-1.5l-5.7-5.3c-.8-.8-2.1-.7-2.8.1-.8.8-.7 2.1.1 2.8l2.7 2.5H5c-1.1 0-2 .9-2 2s.9 2 2 2h9.4l-2.7 2.5c-.5.4-.7 1-.7 1.5s.2 1 .5 1.4c.8.8 2.1.8 2.9.1z"></path></svg></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require('Footer.php'); ?>
</body>
</html>
