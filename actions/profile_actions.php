<?php

session_start();
include("./include.php");

$user_id = $_SESSION['id'];

if (isset($_POST['Action'])) {
    $Action = $_POST['Action'];
}
if (isset($_POST['toggle'])) {
    $toggle = $_POST['toggle'];
    $toggle = trim($toggle);
    $toggle = mysqli_real_escape_string($db, $toggle);
}
if (isset($_POST['currentPassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $currentPassword = trim($currentPassword);
    $currentPassword = mysqli_real_escape_string($db, $currentPassword);
}
if (isset($_POST['newPassword'])) {
    $newPassword = $_POST['newPassword'];
    $newPassword = trim($newPassword);
    $newPassword = mysqli_real_escape_string($db, $newPassword);
}
// _______________________________________________________________________________________________________________

function loadProfile()
{
    global $db;
    global $user_id;

    $getProfileInfo = "SELECT DCusername, DCvorname, profile_image";
    $getProfileInfo_q = mysqli_query($db, $getProfileInfo);

    $fetch = mysqli_fetch_assoc($getProfileInfo_q);
    $username = $fetch['DCusername'];
    $DCvorname = $fetch['DCvorname'];
    $profile_image = $fetch['profile_image'];
}

// _______________________________________________________________________________________________________________

function resetPassword($newPassword, $currentPassword)
{
    global $db;
    global $user_id;

    $currentPasswordHashed = hash("sha256", $currentPassword);
    $checkIfPasswordCorrect = "select DCpassword from registration where id = $user_id";
    $checkIfPasswordCorrect_q = mysqli_query($db, $checkIfPasswordCorrect);
    $fetch = mysqli_fetch_array($checkIfPasswordCorrect_q);
    $pw = $fetch['DCpassword'];

    if ($pw == $currentPasswordHashed) {
        $insertNewPassword = "INSERT INTO registration (DCpassword) VALUES ('$newPassword')";
        $insertNewPassword_q = mysqli_query($db, $insertNewPassword);
        $error = mysqli_error($db, $insertNewPassword_q);
        if ($error) {
            echo $error;
        } else {
            echo 1;
        }
    } else {
        echo 0;
    }
}

// _______________________________________________________________________________________________________________

if ($Action == "loadProfile") {
    loadProfile();
}
if ($Action == "resetPassword") {
    resetPassword($newPassword, $currentPassword);
}
