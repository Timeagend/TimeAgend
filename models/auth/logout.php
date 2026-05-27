<?php

require_once "authFunctions.php";
include_once("../../config/url.php");
include_once("../../config/conection.php");

if (isset($_POST['logout'])) {
    $con  = getDatabaseConnection(); // ← usa a função do seu conection.php
    $auth = new Auth($con);
    $auth->logout();
    exit();
}
?>
