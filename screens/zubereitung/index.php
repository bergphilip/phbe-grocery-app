<?php
session_start();
$user_id = $_SESSION['id'];
if (!isset($user_id)) {
    header("Location: ../index/index.php");
}
$page = "rezepte";
$recipe_id = $_POST['recipe_id'];
if (!isset($recipe_id)) {
    header("Location: ../rezepte/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Zubereitung</title>
    <!-- _________________________Icons ______________________________ -->
    <link rel="icon" type="image/png" href="../../assets/icons/appicon.png">
    <link rel="apple-touch-icon" href="../../assets/icons/appicon.png">
    <!-- _____________________________________________________________ -->
    <script src="../../jQuery/jquery.js"></script>
    <script src="../../js/tata-master/dist/tata.js"></script>
    <script src="../../components/modals/modal.js"></script>
    <script src="../../components/navigation/bottom-navigator.js"></script>
    <script src="../produkte/produkte.js" defer></script>
    <script src="./zubereitung.js" defer></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>
    <script src="../../js/swipes-min.js"></script>
    <script src="../../js/main.js" defer></script>

    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../css_main/main.css" />
    <link rel="stylesheet" href="../../css_main/main_mobile.css" />
    <link rel="stylesheet" href="./zubereitung.css" />
    <link rel="stylesheet" href="./zubereitung_mobile.css" />
    <link rel="stylesheet" href="../../components/modals/modal.css" />
    <link rel="stylesheet" href="../../components/modals/modal_mobile.css" />
    <link rel="stylesheet" href="../../components/navigation/bottom-navigator.css" />
    <link rel="stylesheet" href="../../components/navigation/side-navigator.css" />
</head>

<body onload="loadZubereitung(<?php echo $recipe_id ?>)">
    <?php
    include("../../components/modals/modal.php");
    include("../../components/modals/splash.php");
    include("../../components/navigation/bottom-navigator.php");
    include("../../components/navigation/side-navigator.php");
    ?>
    <input type="hidden" id="page" value="<?php echo $page ?>" />
    <div id="mainContentWrapper" class="main-content-wrapper">
        <div class="topbar">
            <span class="topbar-font">Zubereitung</span>
        </div>
        <div class="row content-main" id="mainContent">
            <div class="main-wrapper"></div>
        </div>
    </div>
</body>

</html>