function loadZubereitung(recipe_id){
    loadSplash();
    setTimeout(function () {
      var url ="../../actions/recipe_actions.php";
      var PostString = `recipe_id=${recipe_id}&Action=loadZubereitung`;    
    
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", url, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      }    

      let response = xhr.responseText;
      let content = document.getElementsByClassName("main-wrapper")[0];

      content.innerHTML = response;
      if (xhr.readyState == 4) {
        hideSplash();  
      }
      setTimeout(() => {
        checkIfInCart();
      }, 100);
    }, 10);
}

// ____________________________________________________________________

const editIngredients = (recipe_id) => {

  loadSplash();
  let width = 500;
  let height = "90%";
  openModal(width, height);
  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");

  modalHeader.innerHTML = `<input type="text" placeholder="Welche Zutaten brauchst du?" id="filterInputMobile" class="input-mobile" onkeyup="searchProductMobile()" />`;


  setTimeout(function () {
      var url ="../../actions/recipe_actions.php";
      var PostString = `recipe_id=${recipe_id}&Action=loadIngredients`;    
    
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", url, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      }    

      let response = xhr.responseText;
      let content = document.getElementsByClassName("main-wrapper")[0];

      modal.innerHTML = response;
      if (xhr.readyState == 4) {
        hideSplash();  
      }
      
    }, 10)
}

// ____________________________________________________________________

const handleIngredientClick = (product_id, recipe_id) => {

      var url ="../../actions/recipe_actions.php";
      var PostString = `recipe_id=${recipe_id}&product_id=${product_id}&Action=handleIngredient`;    
    
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", url, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      }    

      let response = xhr.responseText;

      if (response == 1) {
        addCartClass(product_id);  
      }
      else if (response == 2) {
        removeCartClass(product_id);  
      }
      else{
        tata.error("Zutat hinzufügen fehlgeschlagen", "Bitte erneut versuchen", {
          position: 'tm',
          animate: 'slide',
          duration: 3000
        });
      }
}

// ____________________________________________________________________

const reloadIngredients = () => {
  let recipe_id = document.getElementById("recipe_id").value;
  loadZubereitung(recipe_id);
}

// ____________________________________________________________________

const handleEditCookingStepsPress = () => {
  let spans = document.getElementsByClassName("edit-cooking-steps-span");
  let editBtn = document.getElementsByClassName("edit-zubereitung-btn")[1];
  for (let index = 0; index < spans.length; index++) {
    spans[index].classList.toggle("span-active");
  }
  if (spans[0].classList.contains("span-active")) {
    editBtn.innerHTML = "Fertig";
  }
  else{
    editBtn.innerHTML = "Bearbeiten";
  }
  
}

// ____________________________________________________________________

const editStep = (step_id) => {
  
  let step = document.getElementById(`step${step_id}`);
  currentStepText = step.innerHTML;

  let width = 500;
  let height = "90%";
  openModal(width, height);

  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");

  modalHeader.innerHTML = "Schritt bearbeiten";
  modal.innerHTML = `
  <div class="modal-wrapper">
    <textarea type="text" placeholder="" class="textarea-gen">${currentStepText}</textarea>
    <button onclick="saveStep(${step_id})" class="btn btn-gen">Speichern</button>
  </div>
  `;
}

// ____________________________________________________________________

const saveStep = (step_id) => {
  let newStepText = document.getElementsByClassName("textarea-gen")[0].value;

  if (newStepText < 3 || newStepText > 200) {
    tata.error("Schritt hinzufügen", "Min. 3 Zeichen/ Max. 200 Zeichen", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
  }
  else{
    newStepText = encodeURIComponent(newStepText);

    var url ="../../actions/recipe_actions.php";
    var PostString = `step_id=${step_id}&step=${newStepText}&Action=saveStep`;    
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    
  
    let response = xhr.responseText;
  
    if (response == 1){
      tata.success("Schritt hinzufügen", "Erfolgreich gespeichert", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
      });
      dismissModal();
    }
    else{
      tata.error("Schritt hinzufügen", "Datenbank Fehler", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
      });
    } 
  }
}

// ____________________________________________________________________

const handleDeleteStep = (step_id) => {
  let width = 500;
  let height = "90%";
  openModal(width, height);
  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");

  modalHeader.innerHTML = "Schritt löschen";
  modal.innerHTML = `
    <div class="modal-wrapper">
      <p>Diesen Schritt wirklich löschen?</p>
      <button onclick="deleteStep(${step_id})" class="btn btn-gen">Löschen</button>
    </div>
  `;

}

// ____________________________________________________________________

const deleteStep = (step_id) => {

  var url ="../../actions/recipe_actions.php";
  var PostString = `step_id=${step_id}&Action=deleteStep`;    

  xhr = new XMLHttpRequest();
  if(xhr){
  xhr.open("POST", url, false);
  xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xhr.send(PostString);
  }    

  let response = xhr.responseText;

  if (response == 1){
    tata.success("Schritt löschen", "Erfolgreich gelöscht", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
    dismissModal();
  }
  else{
    tata.error("Schritt löschen", "Datenbank Fehler", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
  }
}

const handleAddNewStep = (step_id) => {

  let width = 500;
  let height = "90%";
  openModal(width, height);

  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");

  modalHeader.innerHTML = "Schritt hinzufügen";
  modal.innerHTML = `
  <div class="modal-wrapper">
    <textarea type="text" resizable="false" class="textarea-gen" placeholder="Schritt eingeben ..."></textarea>
    <button onclick="addNewStep(${step_id})" class="btn btn-gen">Hinzufügen</button>
  </div>
  `;

}

// ____________________________________________________________________

const addNewStep = (step_id) => {
  let newStepText = document.getElementsByClassName("textarea-gen")[0].value;

  if (newStepText < 3 || newStepText > 200) {
    tata.error("Schritt hinzufügen", "Min. 3 Zeichen/ Max. 200 Zeichen", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
  }
  else{
    newStepText = encodeURIComponent(newStepText);

    var url ="../../actions/recipe_actions.php";
    var PostString = `step_id=${step_id}&step=${newStepText}&Action=addNewStep`;    
  
    xhr = new XMLHttpRequest();
    if(xhr){
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(PostString);
    }    
  
    let response = xhr.responseText;
  
    if (response == 1){
      tata.success("Schritt hinzufügen", "Erfolgreich gespeichert", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
      });
      dismissModal();
    }
    else{
      tata.error("Schritt hinzufügen", "Datenbank Fehler", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
      });
    } 
  }
}

// ____________________________________________________________________

const handleDeleteRecipe = (recipe_id) => {

  let width = 500;
  let height = "90%";
  openModal(width, height);

  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");

  modalHeader.innerHTML = "Schritt hinzufügen";
  modal.innerHTML = `
  <div class="modal-wrapper">
    <span>Dieses Rezept wirklich löschen?</span>
    <button onclick="deleteRecipe(${recipe_id})" class="btn btn-gen">Löschen</button>
  </div>
  `;
}

const deleteRecipe = (recipe_id) => {

  var url ="../../actions/recipe_actions.php";
  var PostString = `recipe_id=${recipe_id}&Action=deleteRecipe`;    

  xhr = new XMLHttpRequest();
  if(xhr){
  xhr.open("POST", url, false);
  xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xhr.send(PostString);
  }    

  let response = xhr.responseText;

  if (response == 1){
    tata.success("Rezept löschen", "Erfolgreich gelöscht", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
    window.location.href = "../rezepte";
  }
  else{
    tata.error("Rezept löschen", "Datenbank Fehler", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
  }
}