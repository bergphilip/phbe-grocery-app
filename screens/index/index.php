<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Login</title>
    <!-- _________________________Icons ______________________________ -->
    <link rel="icon" type="image/png" href="../../assets/icons/appicon.png">
    <link rel="apple-touch-icon" href="../../assets/icons/appicon.png">
    <!-- _____________________________________________________________ -->
    <script src="../../jQuery/jquery.js"></script>
    <script src="./index.js" defer></script>
    <script src="../../bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../css_main/main.css" />
    <link rel="stylesheet" href="./index.css" />
    <link rel="stylesheet" href="./index_mobile.css" />
</head>

<body>
    <div class="basic-container left">
        <span><img src="../../assets/img/logo_large.png" class="logo"></span>
        <div class="cart-index">
            <img src="../../assets/img/index_cart.png" class="img-cart" />
            <p class="index-font">Plane deinen Einkauf und lege Rezepte an.
            </p>
        </div>
    </div>
    <div class="basic-container right">
        <div class="mobile-login-header">
            <span><img src="../../assets/img/logo_large.png" class="logo"></span>
        </div>
        <div class="login-wrapper">
            <div class="input-wrapper">
                <div class="login-font">Login</div>
                <div id="wrong-creds" class="wrong-creds"></div>
                <input id="username" class="gen-input" type="text" placeholder="Username eingeben ...">
                <input id="password" class="gen-input" type="password" placeholder="Passwort eingeben ...">
                <button id="btn-gen" class="btn btn-gen">
                    <span style="visibility: hidden;" id="btn-gen-loader" class="spinner-border spinner-border-sm"></span>
                    Einloggen
                </button>
                <label class="toggle">
                    <input id="autolog-toggle" name="autolog" class="toggle__input" type="checkbox">
                    <span class="toggle__label">
                        <span class="toggle__text">Eingeloggt bleiben</span>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("load", () => {
            registerServiceWorker();
        });
        async function registerServiceWorker() {
            if ("serviceWorker" in navigator) {
                try {
                    await navigator.serviceWorker.register("../../sw.js")
                } catch (error) {
                    console.log("SW not available");
                }
            }
        }
    </script>
</body>

</html>