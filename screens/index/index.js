function checkLogin() {   

    resetWrongCreds();
    callLoadingCircle();
    setTimeout(function(){ 

        var toggleBox = document.getElementById("autolog-toggle");
        var btn = document.getElementById("loginBtn");
        // var isLogbook = document.getElementById("isLogbook").value;
        // var is_hd = document.getElementById("isHd").value;
        
        if (toggleBox.checked) {
            var toggleVal = "true";
        }
        else{
            var toggleVal = "false";
        }
        
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;
        
        var theUrl="../../actions/login_actions.php";
        var PostString = "toggle="+toggleVal+"&username="+username+"&password="+password+"&Action=checkLogin";    
        
        xhr = new XMLHttpRequest();
        if(xhr){
        xhr.open("POST", theUrl, false);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send(PostString);
        
        }
    
        var response = xhr.responseText;
        let message = "";
    
        if(response == 1){

            window.location.href="../produkte";

        }else{

            let message = "Eingangsdaten sind falsch";
            callWrongCreds(message);

        }
        //   setTimeout(function(){ document.getElementById("loader").style.display = "none"; }, 100);
    }, 100);
}   

// ____________________________________________________________________

document.addEventListener("keyup", function(event) {

    if (event.keyCode === 13) {
        event.preventDefault();
        checkLogin();
    }
});

// ____________________________________________________________________

var btn = document.getElementById("btn-gen");
btn.addEventListener("click", function() {

    checkLogin();

});

// ____________________________________________________________________

var cookie = ("; " + document.cookie).split("; sessionToken=").pop().split(";").shift();
console.log(cookie);
if (cookie) {

    checkLoginCookie(cookie);

    // document.body.innerHTML = "<html><head><link rel='stylesheet' type='text/css' href='css/loader.css'></head><body><div id='splash' class='splash'></div></body</html>";
}

// ____________________________________________________________________

function checkLoginCookie(cookie) {
    
    var theUrl = "../../actions/login_actions.php";
    var PostString = "cookie=" + cookie + "&Action=checkLoginCookie";

    xhr = new XMLHttpRequest();
    if (xhr) {
        xhr.open("POST", theUrl, false);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(PostString);

    }
    var response = xhr.responseText;

    if (response == 1) {
        window.location.href="../produkte";
    } else {
        callWrongCreds();
    }
}

// ________________________________________________________________________________
function hideSplash(){
    splash = document.getElementById("splash");
    splash.style.display = "none";
}
// ____________________________________________________________________
const callWrongCreds = (message) => {

    resetLoadingCircle();

    let wrong = document.getElementById("wrong-creds");
    wrong.innerHTML = message;
    wrong.style.display = "block";

    setTimeout(function(){ 

        wrong.classList.add("creds__extended");

    }, 100);

};

const resetWrongCreds = () => {
    let wrong = document.getElementById("wrong-creds");
    wrong.classList.remove("creds__extended");
};

const callLoadingCircle = () => {
    document.getElementById("btn-gen-loader").style.visibility = "visible";
};

const resetLoadingCircle = () => {
    document.getElementById("btn-gen-loader").style.visibility = "hidden";
}