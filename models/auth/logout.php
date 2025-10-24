<?php

require_once "authFunctions.php";
include_once("../../config/url.php");

if (isset($_POST['logout'])) {
    $auth = new Auth($con);
    $auth->logout();
    header("Location:" . BASE_URL."/user/login.php"); 
    exit();
}
?>
