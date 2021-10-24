<?php

session_start();
include("../server/connection/dbconnect.php");

if (isset($_POST['Action'])) {
  $Action = $_POST['Action'];
}
if (isset($_POST['toggle'])) {
  $toggle = $_POST['toggle'];
  $toggle = trim($toggle);
  $toggle = mysqli_real_escape_string($db, $toggle);
}
if (isset($_POST['cookie'])) {
  $cookie = $_POST['cookie'];
  $cookie = trim($cookie);
  $cookie = mysqli_real_escape_string($db, $cookie);
}
if (isset($_POST['user_id'])) {
  $user_id = $_POST['user_id'];
  $user_id = trim($user_id);
  $user_id = mysqli_real_escape_string($db, $user_id);
}
if (isset($_POST['password'])) {
  $password = $_POST['password'];
  $password = trim($password, ";{}");
  $password = mysqli_real_escape_string($db, $password);
}
if (isset($_POST['username'])) {
  $username = $_POST['username'];
  $username = trim($username, ";{}");
  $username = mysqli_real_escape_string($db, $username);
}
// _______________________________________________________________________________________________________________
function checkLogin($username, $password, $toggle)
{

  global $db;

  if ($toggle == "true") {

    $password = hash("sha256", $password);

    $checkLogin = "SELECT * FROM registration WHERE DCusername = '$username' AND DCpassword = '$password';";
    $checkLogin_query = mysqli_query($db, $checkLogin);
    $checkLogin_num_rows = mysqli_num_rows($checkLogin_query);
    $row = mysqli_fetch_array($checkLogin_query);

    if ($checkLogin_num_rows !== 0) {
      $vorname = $row['DCvorname'];
      $username = $row['DCusername'];
      $user_id = $row['id'];
      $user_image = $row['profile_image'];
      $is_hd = $row['is_hd'];
      $identifier = $row['identifier'];

      $date = date("d.m.Y");
      $cookie_name = "sessionToken";
      $salt = "Xy77ZZsZ!";
      $cookie_value = $salt . "$vorname" . $user_id . $salt;
      $cookie_value = hash("sha256", $cookie_value);
      setcookie($cookie_name, $cookie_value, strtotime('+180 days'), "/");

      $insertCookie = "UPDATE registration SET session_token = '$cookie_value' WHERE id = $user_id;";
      $q = mysqli_query($db, $insertCookie);

      echo 1;

      $insertIntoLogin = "UPDATE registration SET last_login = '$date' WHERE id = $user_id";
      $insertIntoLogin_query = mysqli_query($db, $insertIntoLogin);

      $_SESSION['DCvorname'] = $vorname;
      $_SESSION['id'] = $user_id;
      $_SESSION['is_hd'] = $is_hd;
      $_SESSION['user_image'] = $user_image;
      $_SESSION['identifier'] = $identifier;
    } else {
      echo "0"; // login false
    }
  } else {

    $password = hash("sha256", $password);
    $checkLogin = "SELECT * FROM registration WHERE DCusername = '$username' AND DCpassword = '$password';";
    $checkLogin_query = mysqli_query($db, $checkLogin);
    $checkLogin_num_rows = mysqli_num_rows($checkLogin_query);
    $row = mysqli_fetch_array($checkLogin_query);
    if ($checkLogin_num_rows !== 0) {
      $vorname = $row['DCvorname'];
      $username = $row['DCusername'];
      $user_id = $row['id'];
      $is_hd = $row['is_hd'];
      $user_image = $row['profile_image'];
      $identifier = $row['identifier'];

      $date = date("d.m.Y");
      echo 1;

      $insertIntoLogin = "UPDATE registration SET last_login = '$date' WHERE id = $user_id";
      $insertIntoLogin_query = mysqli_query($db, $insertIntoLogin);

      $_SESSION['DCvorname'] = $vorname;
      $_SESSION['id'] = $user_id;
      $_SESSION['is_hd'] = $is_hd;
      $_SESSION['img'] = $user_image;
      $_SESSION['identifier'] = $identifier;
    } else {
      echo "0";
    }
  }
  mysqli_close($db);
}

// _______________________________________________________________________________________________________________
function checkLoginCookie($cookie)
{
  global $db;
  $date = date("d.m.Y");

  $checkCookie = "SELECT id, DCvorname, profile_image FROM registration WHERE session_token = '$cookie';";
  $checkCookie_q = mysqli_query($db, $checkCookie);
  $nums = mysqli_num_rows($checkCookie_q);

  if ($nums !== 0) {
    $row = mysqli_fetch_array($checkCookie_q);

    $user_id = $row['id'];
    $user_image = $row['profile_image'];
    $vorname = $row['DCvorname'];

    echo 1;

    $insertIntoLogin = "UPDATE registration SET last_login = '$date' WHERE id = $user_id";
    $insertIntoLogin_query = mysqli_query($db, $insertIntoLogin);

    $_SESSION['DCvorname'] = $vorname;
    $_SESSION['id'] = $user_id;
    $_SESSION['img'] = $user_image;
  } else {
    echo "0";
  }
}
// _______________________________________________________________________________________________________________

if ($Action == "checkLogin") {
  checkLogin($username, $password, $toggle);
}
if ($Action == "checkLoginCookie") {
  checkLoginCookie($cookie);
}
