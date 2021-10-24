// ________________________________________________________________________
function openDashboard(){
    openModal(400, 500);
    let modal = document.getElementById("modalBody");
    let modalHeader = document.getElementById("modalHeaderFont");

    modalHeader.innerHTML = "Dashboard";

    modal.innerHTML = `
        <div class="avatar-wrapper">
         <img class="avatar-image"/>
         <span></span>
        </div>
  
        <div onclick="openLocation('profil')" class="dashboard-tiles">
            <img class="icon dashboard-icon large" src="../../assets/icons/address-card.svg" />
            <span>Profil</span>
        </div>
        <button onclick="logout()" class="mobile-logout-btn btn-gen">Logout</button>
    `;
}

// ________________________________________________________________________

function logout() {
    window.location.href = "../../server/connection/logout.php";
}
// ________________________________________________________________________

function loadSplash() {
    let splash = document.getElementById("splash");
    splash.style.display = "block";
  }
  
  function hideSplash() {
    let splash = document.getElementById("splash");
    splash.style.display = "none";
  }

const openLocation = (location) => {
    window.location.href = `../${location}`;
}

// ________________________________________________________________________

document.getElementById("mainContent").onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.getElementById("mainContent").scrollTop >= 12 || document.documentElement.scrollTop >= 12) {
      document.getElementsByClassName("topbar")[0].classList.add("minified");
      document.getElementsByClassName("topbar-font")[0].classList.add("minified-font");
        if (document.getElementsByClassName("input")[0]) {
            document.getElementsByClassName("input")[0].classList.add("input-minified");      
        }
        if (document.getElementsByClassName("sync-icon-wrapper")[0]) {
            document.getElementsByClassName("sync-icon-wrapper")[0].classList.add("sync-minified");
        }
      
  
    } else {
      document.getElementsByClassName("topbar")[0].classList.remove("minified");
      document.getElementsByClassName("topbar-font")[0].classList.remove("minified-font");

      if (document.getElementsByClassName("sync-icon-wrapper")[0]) {
        document.getElementsByClassName("sync-icon-wrapper")[0].classList.remove("sync-minified");    
      }
      if (document.getElementsByClassName("input")[0]) {
        document.getElementsByClassName("input")[0].classList.remove("input-minified");    
      }
      
    }
  }