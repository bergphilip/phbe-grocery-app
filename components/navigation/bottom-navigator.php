<?php

if ($page == "produkte") {
    $active_produkte = "active";
} else {
    $active_produkte = "";
}
if ($page == "rezepte") {
    $active_rezepte = "active";
} else {
    $active_rezepte = "";
}
if ($page == "freunde") {
    $active_freunde = "active";
} else {
    $active_freunde = "";
}

?>

<div class="bottom-navigator">
    <!-- main.js -->
    <div onclick="openLocation('produkte')" class="navigators <?php echo $active_produkte ?>">
        <img src="../../assets/icons/cart.svg" class="bottom-navigation-icons large">
    </div>
    <div onclick="openLocation('rezepte')" class="navigators <?php echo $active_rezepte ?>">
        <img src="../../assets/icons/utensils.svg" class="bottom-navigation-icons small">
    </div>
    <div onclick="openLocation('freunde')" class="navigators <?php echo $active_freunde ?>">
        <img src="../../assets/icons/users.svg" class="bottom-navigation-icons large">
    </div>
    <div class="navigators" onclick="openDashboard()">
        <img src="../../assets/icons/menu.svg" class="bottom-navigation-icons small">
    </div>

</div>