<?php
date_default_timezone_set("Europe/Berlin");
$browser = $_SERVER['HTTP_USER_AGENT'];

// $db_server = 'localhost';
// $db_benutzer = 'root';
// $db_passwort = 'root';
// $db_name = 'datacalc';

$db = mysqli_connect($db_server, $db_benutzer, $db_passwort);
$dbconnect = mysqli_select_db($db, $db_name);
mysqli_query($db, "SET CHARACTER SET 'utf8'");
mysqli_query($db, "SET SESSION collation_connection ='utf8_unicode_ci'");

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}
