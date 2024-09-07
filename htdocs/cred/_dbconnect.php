<?php
    $dbserver = "localhost";
    $dbusr = "root";
    $dbpass = "";
    $dtatbase = "stardustsii";

    $dbconnect = mysqli_connect($dbserver, $dbusr, $dbpass, $dtatbase);
    if (!$dbconnect) {
        die("Connection Error".mysqli_connect_error());
    }
?>