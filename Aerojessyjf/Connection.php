<?php
    session_start();
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname="Aerojessy";


    $con = mysqli_connect($servername, $username, $password,$dbname);


    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>