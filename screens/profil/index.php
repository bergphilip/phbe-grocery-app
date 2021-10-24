<?php
session_start();
$user_id = $_SESSION['id'];
if (!isset($user_id)) {
    header("Location: ../index/index.php");
}
$page = "profil";
$user_name = $_SESSION['DCvorname'];
$user_tag = $_SESSION['identifier'];
$user_img = $_SESSION['img'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Profil</title>
    <!-- _________________________Icons ______________________________ -->
    <link rel="icon" type="image/png" href="../../assets/icons/appicon.png">
    <link rel="apple-touch-icon" href="../../assets/icons/appicon.png">
    <!-- _____________________________________________________________ -->
    <script src="../../jQuery/jquery.js"></script>
    <script src="../../components/modals/modal.js"></script>
    <script src="../../js/tata-master/dist/tata.js"></script>
    <script src="../../components/navigation/bottom-navigator.js"></script>
    <script src="./profil.js" defer></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>
    <script src="../../js/swipes-min.js"></script>
    <script src="../../js/main.js" defer></script>

    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../css_main/main.css" />
    <link rel="stylesheet" href="./profil.css" />
    <link rel="stylesheet" href="./profil_mobile.css" />
    <link rel="stylesheet" href="../../components/modals/modal.css" />
    <link rel="stylesheet" href="../../components/modals/modal_mobile.css" />
    <link rel="stylesheet" href="../../components/navigation/bottom-navigator.css" />
    <link rel="stylesheet" href="../../components/navigation/side-navigator.css" />
</head>

<body onload="">
    <?php
    include("../../components/modals/modal.php");
    include("../../components/modals/splash.php");
    include("../../components/navigation/bottom-navigator.php");
    include("../../components/navigation/side-navigator.php");
    ?>
    <input type="hidden" id="page" value="<?php echo $page ?>" />
    <div id="mainContentWrapper" class="main-content-wrapper">
        <div class="topbar">
            <span class="topbar-font">Profil</span>
        </div>
        <div class="row content-main" id="mainContent">
            <div class="wrapper wrapper-substract-margin">
                <div class="avatar">
                    <div class="profile-image-wrapper">
                        <img class="profile-image" src="<?php echo "../../assets/img/profile_images/" . $user_img ?>" />
                        <span class=" profile-change-image-btn">
                            <img src="../../assets/icons/edit.svg" class="icon change-image-icon">
                        </span>
                    </div>
                </div>
                <p class="greeting">
                    Hallo, <?php echo $user_name ?>
                </p>
                <p class="tag-wrapper">
                    Dein Tag: <?php echo $user_tag ?>
                </p>
                <div class="input-wrapper">
                    <input id="currentPassword" type="password" class="gen-input" placeholder="Passwort eingeben">
                    <input id="newPassword" type="password" class="gen-input" placeholder="Neues Passwort eingeben">
                    <input id="confirmedNewPassword" type="password" class="gen-input" placeholder="Neues Passwort wiederholen">
                    <button id="resetPasswordBtn" class="btn-gen">Aktualisieren</button>
                </div>
            </div>
        </div>
</body>

</html>