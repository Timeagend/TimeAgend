<?php 

session_start();
require_once '../../config/conection.php'; // $con (MySQLi)     
require_once 'authFunctions.php';

$newVar = new User($con);
$phone = $newVar->getPhoneById($_SESSION['iduser']);
$_SESSION['phone'] = $phone;

echo $phone;