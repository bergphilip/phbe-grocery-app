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

if ($page == "profil") {
    $active_profil = "active";
} else {
    $active_profil = "";
}

?>

<div class="navbar-wrapper">
    <div class="avatar"></div>
    <div class="navbar-gen">
        <ul>
            <li onclick="openLocation('produkte')" class="<?php echo $active_produkte ?>"><img src="../../assets/icons/cubes.svg" class="navbar-icon"></img>Produkte</li>
            <li onclick="renderList()"><img src="../../assets/icons/clipboard-list.svg" class="navbar-icon"></img>Liste</li>
            <li onclick="openLocation('rezepte')" class="<?php echo $active_rezepte ?>"><img src="../../assets/icons/utensils.svg" class="navbar-icon"></img>Rezepte</li>
            <li onclick="openLocation('freunde')" class="<?php echo $active_freunde ?>"><img src="../../assets/icons/users.svg" class="navbar-icon"></img>Freunde</li>
            <li onclick="openLocation('profil')" class="<?php echo $active_profil ?>"><img src="../../assets/icons/address-card.svg" class="navbar-icon"></img>Profil</li>
            <li class="logout-li" onclick="logout()"><img src="../../assets/icons/logout.svg" class="navbar-icon"></img>Logout</li>
        </ul>
    </div>
</div>