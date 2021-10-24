<?php
session_start();
$user_id = $_SESSION['id'];
if (!isset($user_id)) {
    header("Location: ../index/index.php");
}
$user_name = $_SESSION['DCvorname'];
$user_img = $_SESSION['img'];
$page = "produkte";
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Einkaufsliste</title>
    <!-- _________________________Icons ______________________________ -->
    <link rel="icon" type="image/png" href="../../assets/icons/appicon.png">
    <link rel="apple-touch-icon" href="../../assets/icons/appicon.png">
    <!-- _____________________________________________________________ -->
    <script src="../../jQuery/jquery.js"></script>
    <script src="../../components/modals/modal.js"></script>
    <script src="../../components/navigation/bottom-navigator.js"></script>
    <script src="./produkte.js" defer></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>
    <script src="../../js/swipes-min.js"></script>
    <script src="../../js/main.js"></script>
    <script type="text/javascript">
        let user_name = "<?php echo $user_name ?>";
        let user_image = "<?php echo $user_image ?>";
    </script>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../css_main/main.css" />
    <link rel="stylesheet" href="./produkte.css" />
    <link rel="stylesheet" href="./produkte_mobile.css" />
    <link rel="stylesheet" href="../../components/modals/modal.css" />
    <link rel="stylesheet" href="../../components/modals/modal_mobile.css" />
    <link rel="stylesheet" href="../../components/navigation/bottom-navigator.css" />
    <link rel="stylesheet" href="../../components/navigation/side-navigator.css" />
</head>

<body onload="loadCart()">
    <?php
    include("../../components/modals/modal.php");
    include("../../components/modals/splash.php");
    include("../../components/navigation/bottom-navigator.php");
    include("../../components/navigation/side-navigator.php");
    ?>
    <input type="hidden" id="page" value="<?php echo $page ?>" />
    <div id="mainContentWrapper" class="main-content-wrapper">
        <input type="text" placeholder="Suchen ..." id="filterInput" class="input" onkeyup="searchProduct()" />
        <div id="renderListBtn" class="render-list-btn" onclick="renderList()">
            <img src="../../assets/icons/clipboard-list.svg" class="icon btn-icon">
        </div>
        <div id="searchBar" class="searchbar" onclick="openSearchList()">
            <img src="../../assets/icons/search.svg" class="icon btn-icon">
        </div>
        <div class="topbar">
            <span class="topbar-font">Produkte</span>
        </div>
        <div class="row content-main" id="mainContent">
        </div>
    </div>
</body>

</html>