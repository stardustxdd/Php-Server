<?php
 session_start();
 
    $_SESSION['loggedin'] = false;
    header("location: index.php");
    exit;
 
    require "element/_nav.php";
?>