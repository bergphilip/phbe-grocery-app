const loadFreunde = () => {

    let main = document.getElementById("mainContent");

    var url="../../actions/freunde_actions.php";
    var PostString = "Action=loadFreunde";    
  
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", url, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      
      } 
      response = xhr.responseText;
      console.log(response);
      main.innerHTML = response;    
}

const handleDeleteFriend = (friends_table_id, friend_name) => {

    let width = 500;
    let height = "90%";
    openModal(width, height);

    let modal = document.getElementById("modalBody");
    let modalHeader = document.getElementById("modalHeaderFont");

    modalHeader.innerHTML = "Kontakt löschen";
    modal.innerHTML = `
    <div class="modal-wrapper">
         <p class="delete-friend-font">Möchtest du ${friend_name} wirklich aus deiner Liste entfernen?</p>
         <button class="btn btn-gen" onclick="deleteFriend(${friends_table_id})">Löschen</button>
    </div>
    `;
}


const deleteFriend = (friends_table_id) => {

    var url="../../actions/freunde_actions.php";
    var PostString = `friends_table_id=${friends_table_id}&Action=deleteFriend`;    
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    

    let response = xhr.responseText;

    if (response == 1) {
        tata.success("Kontakt entfernen", "Kontakt wurde entfernt", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
          });

          dismissModal();
          loadFreunde();
    }
    else{
        tata.error("Freund entfernen fehlgeschlagen", "Bitte erneut versuchen", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });
    }
}

const handleSearchFriend = () => {
 
    let width = 500;
    let height = "90%";
    openModal(width, height);
    
    let modal = document.getElementById("modalBody");
    let modalHeader = document.getElementById("modalHeaderFont");

    modalHeader.innerHTML = "Kontakt suchen";
    modal.innerHTML = `
    <div class="modal-wrapper">
        <input class='gen-input' type='text' placeholder='Kontakte anhand des Keys finden ...' autofocus onkeyup='searchFriend(this.value)' />
        <div class="friend-search-result-wrapper">
        </div>
    </div>
    `;   

}


const searchFriend = (friend_key) => {

    let resultWrapper = document.getElementsByClassName("friend-search-result-wrapper")[0];

    var url="../../actions/freunde_actions.php";
    var PostString = `friend_key=${friend_key}&Action=searchFriend`; 
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    

    let response = xhr.responseText; 
    
    if (response != "error") {
        resultWrapper.innerHTML = response;
    }
}

const addFriend = (friend_id) => {
    var url="../../actions/freunde_actions.php";
    var PostString = `friend_id=${friend_id}&Action=addFriend`;    
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    

    let response = xhr.responseText; 

    if (response == 1){
        let addFriendIcon = document.getElementById("addFriendIcon");
        addFriendIcon.src = "../../assets/icons/pending.svg";
        addFriendIcon.setAttribute("onclick", function noCall(){
            return false;
        })

        tata.success("Freund hinzufügen", "Anfrage wurde gesendet", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });  
    }
    else{
        tata.error("Freund hinzufügen fehlgeschlagen", "Bitte erneut versuchen", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });       
    }
}


const acceptFriend = (friends_table_id) => {
    var url="../../actions/freunde_actions.php";
    var PostString = `friends_table_id=${friends_table_id}&Action=acceptFriend`;    
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    

    let response = xhr.responseText;

    if (response == 1){
        tata.success("Freundschaftsanfrage", "Anfrage wurde angenommen", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });  
        loadFreunde();
    }
    else{
        tata.error("Freundschaftsanfrage", "Annehmen fehlgeschlagen", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });       
    }
}

const handleEditFriend = (friend_id) => {

    let width = 500;
    let height = "90%";
    openModal(width, height);

    var url="../../actions/freunde_actions.php";
    var PostString = `friend_id=${friend_id}&Action=manageRights`;    
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    

    let response = xhr.responseText;

    if (response != "error") {
        let modal = document.getElementById("modalBody");
        let modalHeader = document.getElementById("modalHeaderFont");
    
        modalHeader.innerHTML = "Rechte erteilen";
        modal.innerHTML = `
        <div class="modal-wrapper wrapper-extra-height">
             ${response}
        </div>
        `;
    }
    else{
        tata.error("Datenbank Fehler", "Bitte erneut versuchen", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });  
    }   
}


const updateRight = (friend_rights_id, handler_description, is_shared) => {

    var url="../../actions/freunde_actions.php";
    var PostString = `friend_rights_id=${friend_rights_id}&handler_description=${handler_description}&is_shared=${is_shared}&Action=updateRight`;   
    
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    

    let response = xhr.responseText;

    if (response != "error") {
        tata.success("Liste teilen", "Status wurde geändert", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });  
    }
    else{
        tata.error("Datenbank Fehler", "Bitte erneut versuchen", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
        });  
    } 
}


const handleBlockedSharing = () => {
    tata.warn("Liste teilen", "Dein Kontakt erlaubt kein Teilen", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
    }); 
}