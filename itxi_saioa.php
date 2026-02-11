<?php
session_start();

// Saioko aldagai guztiak suntsitu
$_SESSION = array();

// Saioa suntsitu
session_destroy();

// Orrialde nagusira birbideratu
header("Location: SARRERA.php");
exit;
?>