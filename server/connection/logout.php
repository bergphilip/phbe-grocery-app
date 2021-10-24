<?php
include("include/dbconnect.php");
session_start();
$user_id = $_SESSION['id'];

if (!isset($user_id)) {
  header("location: index.php");
  exit();
}
if (isset($_COOKIE['sessionToken'])) {
  $deleteCookie = "UPDATE registration SET session_token = NULL WHERE id = $user_id;";
  $q = mysqli_query($db, $deleteCookie);
  setcookie("sessionToken", "", strtotime('-1 days'), "/");
}
session_destroy();
header("location: ../../screens/index/");
