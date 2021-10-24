
const resetPassword = () => {
    let currentPassword = document.getElementById("currentPassword").value;
    let newPassword = document.getElementById("newPassword").value;
    let confirmedNewPassword = document.getElementById("confirmedNewPassword").value;

    let currentPasswordEl = document.getElementById("currentPassword");
    let newPasswordEl = document.getElementById("newPassword");
    let confirmedNewPasswordEl = document.getElementById("confirmedNewPassword");

    if (currentPassword == "" || newPassword == "" || confirmedNewPassword == "") {
        tata.error('Passwort ändern','Alle Felder ausfüllen', {
            position: 'tm',
            animate: 'slide',     
        });
    }
    else{
        if (newPassword != confirmedNewPassword) {
            tata.error('Passwort ändern', 'Passwörter stimmen nicht überein', {
                position: 'tm',
                animate: 'slide',     
            });
        }
        else{
            var url ="../../actions/profile_actions.php";
            let PostString = `newPassword=${newPassword}&currentPassword=${currentPassword}&Action=resetPassword`;
            
            xhr = new XMLHttpRequest();
            if(xhr){
            xhr.open("POST", url, false);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.send(PostString);
            }
      
            let response = xhr.responseText;

            if (response == 1) {
                tata.success("Passwort ändern", "Passwort geändert", {
                    position: 'tm',
                    animate: 'slide',
                });
                currentPasswordEl.value = "";
                newPasswordEl.value = "";
                confirmedNewPasswordEl.value = "";
            }
            else if (response == 0){
                tata.error('Passwort ändern','Altes Passwort falsch',  {
                    position: 'tm',
                    animate: 'slide',     
                });
            }
            else{
                tata.warning('Warnung' ,'Datenbank Fehler',{
                    position: 'tm',
                    animate: 'slide',    
                });
            }
        }
    }
}

let resetBtn = document.getElementById('resetPasswordBtn');
resetBtn.addEventListener('click', resetPassword);