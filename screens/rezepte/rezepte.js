function loadRezepte(){
    loadSplash();
    setTimeout(function () {
      var url ="../../actions/recipe_actions.php";
      var PostString = "Action=loadRecipes";    
      console.log(PostString);
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", url, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      }

      let response = xhr.responseText;
      let mainContent= document.getElementById("mainContent");

      mainContent.innerHTML = response;
      if (xhr.readyState == 4) {
        hideSplash();  
      }
      
    }, 10)
}

// _____________________________________________________________________________

const openRecipe = (recipe_id) => {

  let form = document.createElement("form");
  const field = document.createElement("input");

  field.setAttribute("type", "hidden");
  field.setAttribute("name", "recipe_id");
  field.setAttribute("value", recipe_id);
  form.appendChild(field);
  document.body.appendChild(form);
  form.action = "../zubereitung/index.php";
  form.method = "post";
  form.submit();
}

// _____________________________________________________________________________

const handleAddNewRecipe = () => {

  let width = 500;
  let height = "90%";
  openModal(width, height);
  
  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");
  let select = "";

  let groupArr = [

    {
      "key": "1",
      "name": "Frühstück"
    },
    {
      "key": "2",
      "name": "Mittagessen"
    },
    {
      "key": "3",
      "name": "Abendessen"
    },
    {
      "key": "4",
      "name": "Getränke"
    }
  ];

  for (let index = 0; index < groupArr.length; index++) {
    select += `<option value="${groupArr[index].key}">${groupArr[index].name}</option>`;
  }

  modalHeader.innerHTML = "Rezept hinzufügen";
  modal.innerHTML = `
  <form id="formFile" class="form">
  <input onchange="" type='file' id='file' name="file" style="display: none;" accept="image/*" />
  <div class="wrapper-modal-gen">
   <input placeholder="Rezeptnamen eingeben ..." name='recipe_name' class="gen-input" type="text" value="" />
    <select name='meal_category_id' class="select-gen">
     ${select}
    </select>
    <div class="image-preview" id="imagePreview">
    <span class="preview-text" id="imagePreviewText">Noch kein Bild ausgewählt (optional)</span>
    <img src="" class="image-preview__default" id="imgPreviewReal">
    <img id="addBtn" onclick="triggerFileClick()" class="add-photo-btn icon" src="../../assets/icons/image.svg">
  </div>
    </form>
    <div id="addProductBtn" onclick="addNewRecipe()" class="btn btn-gen">Hinzufügen</div>
  </div>
  `;
  addListener();
}

// _____________________________________________________________________________

const triggerFileClick = () => {
  document.getElementById("file").click();
}
// _____________________________________________________________________________

const addNewRecipe = () => {
  const form = document.getElementById("formFile");
  let inputVal = document.getElementsByClassName("gen-input")[0].value;

  if (inputVal.length > 20 || inputVal.length < 3) {
    tata.warn("Produkt hinzufügen", "Min. 3, Max. 50 Zeichen", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
  }
  else{
    var formdata = new FormData(form);
    formdata.append("Action", "addNewRecipe");
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "../../actions/recipe_actions.php", false);
    xhr.send(formdata);
    
    result = xhr.responseText;
    console.log(formdata);
  
    if (result == 1) {
      loadRezepte();
      dismissModal();
    }
    else{
      tata.warn("Datenbank Fehler", "Bitte versuche es erneut", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
      });
    }
  }
}

// ________________________________________________________________________

const addListener = () => {
  const file = document.getElementById("file");
  const previewFile = document.getElementById("imagePreview");
  const previewText = document.getElementById("imagePreviewText");
  const previewImg = document.getElementById("imgPreviewReal");

  file.addEventListener("change", function() {
      const fileNew = this.files[0];
      document.getElementById("addBtn").style.zIndex = "-1";

      if (fileNew) {
          const reader = new FileReader();
          previewText.style.display = "none";

          reader.addEventListener("load", function() {
              previewImg.setAttribute("src", this.result);
          });

          reader.readAsDataURL(fileNew);
      } else {
          previewText.style.display = "block";
      }
  });
};