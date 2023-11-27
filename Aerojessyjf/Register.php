<?php
include_once('Connection.php');

$firstE = $lastE = $emailE = $mobileE = $passwordE = "";
$first = $last = $email = $mobile = $password = "";
$mark = false;

if (isset($_GET['submit'])) {
    if (empty($_GET["first"])) {
        $firstE = "Enter first name";
        $mark = true;
    } else {
        $first = test_input($_GET["first"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $first)) {
            $firstE = "Enter Correct first name Format";
            $mark = true;
        }
    }

    if (empty($_GET["last"])) {
        $lastE = "Enter last name";
        $mark = true;
    } else {
        $last = test_input($_GET["last"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $last)) {
            $lastE = "Enter Correct last name Format";
            $mark = true;
        }
    }

    if (empty($_GET["email"])) {
        $emailE = "Enter Email";
        $mark = true;
    } else {
        $email = test_input($_GET["email"]);
        if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
            $emailE = "Enter Correct Email Format";
            $mark = true;
        }
    }

    if (empty($_GET["mobile"])) {
        $mobileE = "Enter Mobile";
        $mark = true;
    } else {
        $mobile = test_input($_GET["mobile"]);
        if (!preg_match("/^[0-9]{10}+$/", $mobile)) {
            $mobileE = "Enter 10 digit number";
            $mark = true;
        }
    }

    if (empty($_GET["password"])) {
        $passwordE = "Enter Password";
        $mark = true;
    } else {
        $password = test_input($_GET["password"]);
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}+$/", $password)) {
            $passwordE = "Enter Correct password Format()";
            $mark = true;
        }
    }

    if (!$mark) {
        $query = "SELECT ID_Usuario FROM usuario WHERE Correo_Electronico='" . $email . "';";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO usuario(Nombre_Usuario, Contraseña, Correo_Electronico, Numero_Telefono) 
                      VALUES('" . $first . "', '" . $password . "', '" . $email . "', " . $mobile . ");";
            echo $query;

            $result = mysqli_query($con, $query);

            if (mysqli_affected_rows($con) > 0) {
                session_destroy();
                session_start();

                $_SESSION['user'] = mysqli_insert_id($con);
                $_SESSION['name'] = $first;

                if (isset($_GET['flightId'])) {
                    header("Location:Booking.php?flightId=" . $_GET['flightId']);
                } else {
                    header("Location:Home.php");
                }
            } else {
                echo "query failed";
            }
        } else {
            $passwordE = "Email already exists";
        }
    } else {
        echo "hi";
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="Login.css"/>
    <link rel="stylesheet" type="text/css" href="header.css"/>
    <style>
        header {
            background-color: #02122c;
            /* box-shadow: 2px 2px #02122c; */
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
<?php require('Header.php'); ?>
<div class="container">
    <h1>Registration Page</h1>
    <p class="errorN"> * Required Fields </p><br>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
        <div>
            <label>First Name:</label><br>
            <input type="text" name="first" value="<?php echo $first; ?>">
            <br><span class="error">* <?php echo $firstE; ?> </span><br><br>
        </div>
        <div>
            <label>Last Name:</label><br>
            <input type="text" name="last" value="<?php echo $last; ?>">
            <br><span class="error">* <?php echo $lastE; ?> </span><br><br>
        </div>
        <div>
            <label>Email:</label><br>
            <input type="text" name="email" value="<?php echo $email; ?>">
            <br><span class="error">* <?php echo $emailE; ?> </span><br><br>
        </div>
        <div>
            <label>Mobile:</label><br>
            <input type="text" name="mobile" value="<?php echo $mobile; ?>">
            <br><span class="error">* <?php echo $mobileE; ?> </span><br><br>
        </div>
        <div class="inp">
            <label>Password:</label><br>
            <input type="text" name="password" value="<?php echo $password; ?>">
            <br><span class="error">* <?php echo $passwordE; ?> </span><br><br>
        </div>
        <button type="submit" class="submit" name="submit">Submit</button>
    </form>
    Have an account??<a href="Login.php" style="color:#200220">Login</a>
</div>
</body>
</html>
 