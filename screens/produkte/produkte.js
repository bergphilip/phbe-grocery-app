

function checkIfMobile(){
  let check = false;
(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
return check;
}

// ________________________________________________________________________

function loadCart() {

  loadSplash();
    setTimeout(function () {
      var theUrl="../../actions/cart_actions.php";
      var PostString = "Action=loadCart";    
      
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", theUrl, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      
      }

      let response = xhr.responseText;
      let mainWrapper = document.getElementById("mainContent");

      mainWrapper.innerHTML = response;

      checkIfInCart();
      checkIfListItems();
      if (xhr.readyState == 4) {
        hideSplash();  
      }
      
    }, 10)
}

// ________________________________________________________________________

function checkIfInCart(){

  let productCount = document.getElementsByClassName("count-provider");
  let productsArr = JSON.parse(localStorage.getItem('products'));
  let itemCounter = document.getElementById("itemCounter");

  if (productsArr != null) {
    let listItemCount = "";
    let product_name = "";
    for (let i = 0; i <= productCount.length - 1; i++) {
      product_name = productCount[i].value;
  
      if(productsArr.includes(product_name)){

        listItemCount++;

        product_arr = product_name.split("%");
        product_id = product_arr[1];

        addCartClass(product_id);

        let isInCart = document.getElementById(`productCount${product_id}`);
        isInCart.value = 1;
      }
    }
    if (itemCounter) {
      itemCounter.innerText = listItemCount;  
    } 
  }
} 

// ________________________________________________________________________

function handleProductClick(product_id, product_name, productGroup){
  
  product_name += `%${product_id}%${productGroup}`;
  let isInCart = document.getElementById(`productCount${product_id}`);
  let isInCartVal = isInCart.value;

  if (isInCartVal == 0) {
    addToCart(product_id, product_name, isInCart);
  } else {
    removeFromCart(product_id, product_name, isInCart, 0);
  }
}

// ________________________________________________________________________

function removeCartClass(product_id){
  let product = document.getElementById(`product${product_id}`);
  product.classList.remove("is-in-cart");
}

// ________________________________________________________________________


function addCartClass(product_id){
  let product = document.getElementById(`product${product_id}`);
  product.classList.add("is-in-cart");
}

// ________________________________________________________________________

function removeFromCart(product_id, product_name, isInCart, isListItem){

  if (isListItem == 1) {
    let listItem = document.getElementById(`ck-button${product_id}`);
    listItem.style.height = "0px";

    setTimeout(function () {
      listItem.style.display = "none";
    }, 100)
  }

  if (isInCart == 0) {
    isInCart = document.getElementById(`productCount${product_id}`);
  }

  let products = JSON.parse(localStorage.getItem('products'));

  const index = products.indexOf(product_name);
  if (index > -1) {
    products.splice(index, 1);
  }
  isInCart.value = 0;
  localStorage.setItem('products', JSON.stringify(products));

  removeCartClass(product_id);
  decreaseCounter();
}

// ________________________________________________________________________

function addToCart(product_id, product_name, isInCart){
  
  if(localStorage.getItem('products') == null){
    localStorage.setItem('products', '[]');    
  }
  let products = JSON.parse(localStorage.getItem(`products`));
  products.push(product_name);

  localStorage.setItem(`products`, JSON.stringify(products));
  isInCart.value = 1;

  addCartClass(product_id);
  increaseCounter();
}

// ________________________________________________________________________

const increaseCounter = () => {
  let itemCounter = document.getElementById("itemCounter").innerHTML;
  if (itemCounter != "") {
    let counterValue = parseInt(itemCounter);
    newItemCounter = counterValue + 1;
    document.getElementById("itemCounter").innerHTML = newItemCounter;
  }
  else{
    let newItemCounter = 1;
    document.getElementById("itemCounter").innerHTML = newItemCounter;
  }
  checkIfListItems();
}

// ________________________________________________________________________

const decreaseCounter = () => {
  let itemCounter = document.getElementById("itemCounter").innerHTML;
  if (itemCounter != "") {
    let counterValue = parseInt(itemCounter);
    newItemCounter = counterValue - 1;
    document.getElementById("itemCounter").innerHTML = newItemCounter;
  }
  checkIfListItems();
}

// ________________________________________________________________________

const checkIfListItems = () => {
  let itemCounter = document.getElementById("itemCounter").innerHTML;
  let counterValue = parseInt(itemCounter);
  if (counterValue > 0) {
    document.getElementById("itemCounter").style.display = "block";
  }
  else{
    document.getElementById("itemCounter").style.display = "none";
  }
}

// ________________________________________________________________________

function appendListGroup(){
  let key, group;
  let products = JSON.parse(localStorage.getItem('products'));

  let groupArr = [

    {
      "key": "1",
      "name": "Obst und Gemüse"
    },
    {
      "key": "2",
      "name": "Milchprodukte"
    },
    {
      "key": "3",
      "name": "Haushalt und Pflege"
    },
    {
      "key": "4",
      "name": "Fleisch und Fisch"
    },
    {
      "key": "5",
      "name": "Backwaren "
    },
    {
      "key": "6",
      "name": "Teigwaren"
    },
    {
      "key": "7",
      "name": "Sonstiges"
    },
    {
      "key": "8",
      "name": "Getränke"
    },
    {
      "key": "9",
      "name": "Tiefkühlwaren"
    }
  ];

  let groupWrap = "";
  let groupName = "";
  let groupWrapCheck = "";

  for (let i = 0; i < products.length; i++) {

    productDefault = products[i];
    productArray = products[i].split('%');

    product = productArray[0];
    product_id = productArray[1];
    group_id = productArray[2];

    for (let j = 0; j < groupArr.length; j++) {
      groupWrapCheck = document.getElementById(`group${groupArr[j].key}`);
      if (groupWrapCheck == null) {
          groupWrap = document.createElement('div');
          groupWrap.setAttribute("class", "group-wrapper");
          groupWrap.setAttribute("id", `group${groupArr[j].key}`);
          document.getElementById("modalBody").appendChild(groupWrap);

          groupNameSpan = document.createElement('span');
          groupNameSpan.setAttribute("class", "group-wrapper-name");
          groupNameSpan.innerHTML = groupArr[j].name;
          groupWrap.appendChild(groupNameSpan);
      }
    }
  }
}

// ________________________________________________________________________

function renderList(){

  let width = 500;
  let height = "90%";
  openModal(width, height);
  
  let products = JSON.parse(localStorage.getItem('products'));
  let isInCart = "";
  let product_id = "";
  let product = "";
  let product_array = "";
  
  let arrayLength = products.length;
  let html = "";

  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");
  let modalConfirmButton = document.getElementById("modalConfirmButton");
  let modalDismissButton = document.getElementById("modalDismissButton");
  let groupWrap, ckButton;

  let itemCounter = document.getElementById("itemCounter").innerText;
  itemCounter = parseInt(itemCounter);


  if (isNaN(itemCounter)) {
    itemCounter = 0;
  }

  modalHeader.innerHTML = `Einkaufsliste <span id="itemCounterHeader" class="item-count-header" style="display: block;">${itemCounter}</span>`;
  modalDismissButton.style.display = "none";
  modalConfirmButton.addEventListener("click", () => { 
    dismissModal();
  });


  let itemCounterHeader = document.getElementById("itemCounterHeader");
  if (itemCounter == 0) {
    itemCounterHeader.style.backgroundColor = "#2bb23e";
  } else {
    itemCounterHeader.style.backgroundColor = "#ff6f6f";
  }

  modalConfirmButton.innerHTML = "<img class='button-icon' src='../../assets/icons/success.svg'>";

  if (typeof products !== 'undefined' && products.length > 0) {
    appendListGroup();    
    for (var i = 0; i < arrayLength; i++) {
      
      productDefault = products[i];
      productArray = products[i].split('%');

      product = productArray[0];
      product_id = productArray[1];
      group_id = productArray[2];

      groupWrap = document.getElementById(`group${group_id}`);
      ckButton = document.createElement('div');
      ckButton.setAttribute("class", "ck-button");
      ckButton.setAttribute("id", `ck-button${product_id}`);

      ckButton.innerHTML = `
      <label onclick="removeFromCart( ${product_id}, '${productDefault}', 0, 1), transferItemCount()">
        <span>${product}</span>                        
      </label>
      `;
      groupWrap.appendChild(ckButton);
    }
    checkIfGroupHasChild();
  }
  else{
      modal.innerHTML = `
      <span class='no-products'>
          Noch keine Produkte in der Liste
      </span>`;
    }
  checkIfListItems();
}

// ________________________________________________________________________
const checkIfGroupHasChild = () => {
  let groups = document.getElementsByClassName("group-wrapper");

  for (let index = 0; index < groups.length; index++) {
    
    if (document.getElementsByClassName("group-wrapper")[index].childNodes.length < 2) {
      document.getElementsByClassName("group-wrapper")[index].style.display = "none";
    }
    else{
      document.getElementsByClassName("group-wrapper")[index].style.display = "flex";
    }

  }
}
// ________________________________________________________________________

const transferItemCount = () => {
  let itemCounter = document.getElementById("itemCounter").innerHTML;
  document.getElementById("itemCounterHeader").innerHTML = itemCounter;
}

// ________________________________________________________________________

function searchProduct() {
  var div, input, filter, ul, li, a, i, txtValue;
  input = document.getElementById("filterInput");
  filter = input.value.toUpperCase();

  let mainContentWrapper = document.getElementById("mainContent");
  let productWrapper = mainContentWrapper.getElementsByClassName("product-wrapper");
  let productCounter = 0;

  let groups = document.getElementsByClassName("group-name");
  if (input.value == "") {
    for (let index = 0; index < groups.length; index++) {
      groups[index].style.display = "block";
    }
    mainContentWrapper.style.top = "0px";
  } else{
    for (let index = 0; index < groups.length; index++) {
      groups[index].style.display = "none";
    }
    mainContentWrapper.style.top = "50px";
  }

  for (i = 0; i < productWrapper.length; i++) {
      productName = productWrapper[i].getElementsByClassName("product-name")[0];
      txtValue = productName.textContent || productName.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
          productWrapper[i].style.display = "";
          productCounter++;
      } else {
          productWrapper[i].style.display = "none";
      }
  }
  if(productCounter == 0){
    let newProduct = document.getElementsByClassName("new-product-field")[0];
    if (!newProduct) {
      let newAppendProduct = document.createElement("div");
      newAppendProduct.setAttribute("class", "new-product-field");
      newAppendProduct.innerHTML = `
        <p class="new-product-font">${input.value} ist noch nicht in deiner Liste!</p>
        <button onclick="handleAddNewProductClick('${input.value}')" class="btn btn-gen">Hinzufügen</button>
      `;
      mainContentWrapper.appendChild(newAppendProduct);
    }
    else{
      document.getElementsByClassName("new-product-font")[0].innerHTML = `${input.value} ist noch nicht in deiner Liste!`;
    }  
  }
  else{
    let newProduct = document.getElementsByClassName("new-product-field")[0];
    if (newProduct) {
      newProduct.parentNode.removeChild(newProduct);
    }
  }
}

// ________________________________________________________________________

function openSearchList(){
  openModal(500, "80%");
  let modal = document.getElementById("modalBody");
  let modalHeaderFont = document.getElementById("modalHeaderFont");
  let modalConfirmButton = document.getElementById("modalConfirmButton");
  let modalDismissButton = document.getElementById("modalDismissButton");

  modalHeaderFont.innerHTML = `<input type="text" placeholder="Was möchtest du kaufen?" id="filterInputMobile" class="input-mobile" onkeyup="searchProductMobile()" />`;

  let products = document.getElementsByClassName("product-wrapper");
  let countProvider = document.getElementsByClassName("count-provider");

  let content = "";
  let product_id;
  let counter;
  let group_id;
  let product_name;
  
  for (let i = 0; i < products.length; i++) {
    arr = countProvider[i].value.split("%");
    product_name = arr[0];
    product_id = arr[1];
    group_id = arr[2];

    img_url = document.getElementsByClassName("product-image")[i].src;

    content += `<div class='product-wrapper list-item' onclick='handleProductClick(${product_id}, \"${product_name}\", ${group_id}), checkIfInCart()'>
    <div class='product-overlay' id='product${product_id}'>
      <img src='../../assets/icons/success.svg' class='product-icon'>
    </div>  
        <input type='hidden' value='0' id='productCount${product_id}'/>
        <input class='count-provider' type='hidden' value='${product_name}%${product_id}%${i}'/>
        <img class='product-image' src='${img_url}' onerror=this.src='../../assets/img/products/cart_black.png';>
        <p class='product-name'>${product_name}</p>
    </div>`;
  }

  modal.innerHTML = content;
  checkIfInCart();
  document.getElementById("filterInputMobile").focus();
}

// ________________________________________________________________________

function searchProductMobile() {
  var div, input, filter, ul, li, a, i, txtValue;
  input = document.getElementById("filterInputMobile");
  filter = input.value.toUpperCase();

  let mainContentWrapper = document.getElementById("modalBody");
  let productWrapper = mainContentWrapper.getElementsByClassName("product-wrapper");

  let productCounter = 0;
 
  for (i = 0; i < productWrapper.length; i++) {
      productName = productWrapper[i].getElementsByClassName("product-name")[0];
      txtValue = productName.textContent || productName.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
          productWrapper[i].style.display = "";
          productCounter++;
      } else {
          productWrapper[i].style.display = "none";
      }
  }
  if(productCounter == 0){
    let newProduct = document.getElementsByClassName("new-product-field")[0];
    if (!newProduct) {
      let newAppendProduct = document.createElement("div");
      newAppendProduct.setAttribute("class", "new-product-field");
      newAppendProduct.innerHTML = `
        <p class="new-product-font">${input.value} ist noch nicht in deiner Liste!</p>
        <button id="addProductBtn" onclick="handleAddNewProductClick('${input.value}')" class="btn btn-gen">Hinzufügen</button>
        
      `;
      mainContentWrapper.appendChild(newAppendProduct);
    }
    else{
      document.getElementsByClassName("new-product-font")[0].innerHTML = `${input.value} ist noch nicht in deiner Liste!`
      document.getElementById("addProductBtn").setAttribute("onclick", `handleAddNewProductClick('${input.value}')`);
      ;
    }  
  }
  else{
    let newProduct = document.getElementsByClassName("new-product-field")[0];
    if (newProduct) {
      newProduct.parentNode.removeChild(newProduct);
    }
  }
}

// ________________________________________________________________________

const handleAddNewProductClick = (newProduct) => {
  
  let modal = document.getElementById("modalBody");
  let modalHeader = document.getElementById("modalHeaderFont");
  let select = "";

  let groupArr = [

    {
      "key": "1",
      "name": "Obst und Gemüse"
    },
    {
      "key": "2",
      "name": "Milchprodukte"
    },
    {
      "key": "3",
      "name": "Haushalt und Pflege"
    },
    {
      "key": "4",
      "name": "Fleisch und Fisch"
    },
    {
      "key": "5",
      "name": "Backwaren "
    },
    {
      "key": "6",
      "name": "Teigwaren"
    },
    {
      "key": "7",
      "name": "Sonstiges"
    },
    {
      "key": "8",
      "name": "Getränke"
    },
    {
      "key": "9",
      "name": "Tiefkühlwaren"
    }
  ];

  for (let index = 0; index < groupArr.length; index++) {
    select += `<option value="${groupArr[index].key}">${groupArr[index].name}</option>`;
  }

  modalHeader.innerHTML = "Produkt hinzufügen";
  modal.innerHTML = `
  <form id="formFile" class="form">
  <input onchange="" type='file' id='file' name="file" style="display: none;" accept="image/*" />
  <div class="wrapper-modal-gen">
   <input name='productName' class="gen-input" type="text" value="${newProduct}" />
    <select name='productGroup' class="select-gen">
     ${select}
    </select>
    <div class="image-preview" id="imagePreview">
    <span class="preview-text" id="imagePreviewText">Noch kein Bild ausgewählt (optional)</span>
    <img src="" class="image-preview__default" id="imgPreviewReal">
    <img id="addBtn" onclick="triggerFileClick()" class="add-photo-btn icon" src="../../assets/icons/image.svg">
  </div>
    </form>
    <div id="addProductBtn" onclick="addNewProduct()" class="btn btn-gen">Hinzufügen</div>
  </div>
  `;
  addListener();
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

// ________________________________________________________________________

let iconSync = document.getElementById('iconSync');
let iconCloud = document.getElementById('iconCloud');

const fetchSyncItems = () => {

  let online = window.navigator.onLine;
  
  iconSync.classList.toggle("icon-rotate");


  setTimeout(function (){
    var theUrl="../../actions/cart_actions.php";
    var PostString = "Action=fetchSyncItems";    
  
    if (online) {
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", theUrl, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      
      }
  
      let response = xhr.responseText;
  
      if (response != "0") {
        if (response != "") {
          localStorage.setItem('products', (response));  
          tata.success("Liste synchronisieren", "Synchronisierung erfolgreich", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
          });
          loadCart();
          
        }
        else{
          tata.warn("Synchronisieren fehlgeschlagen", "Die Liste ist leer", {
            position: 'tm',
            animate: 'slide',
            duration: 3000
          });
        }
      }
      else{
        tata.error("Liste synchronisieren", "Synchronisierung fehlgeschlagen", {
          position: 'tm',
          animate: 'slide',
          duration: 3000
        });
      }
    } else {
      tata.warn("Du bist offline", "Bitte später versuchen", {
        position: 'tm',
        animate: 'slide',
        duration: 3000
      });
    }
  }, 10);
}
// ________________________________________________________________________

const uploadSyncItems = () => {

  let online = window.navigator.onLine;

  var theUrl="../../actions/cart_actions.php";
  let syncString = localStorage.getItem('products');
  syncString = encodeURIComponent(syncString);
  var PostString = `sync_string=${syncString}&Action=uploadSyncItems`;

    if (online) {
      xhr = new XMLHttpRequest();
      if(xhr){
      xhr.open("POST", theUrl, false);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send(PostString);
      }
      let response = xhr.responseText;

      if (response != "error") {
        tata.success("Liste hochladen", "Synchronisierung erfolgreich", {
          position: 'tm',
          animate: 'slide',
          duration: 3000
        });  

        console.log(response);
      }
      else{
        tata.error("Synchroniesierung fehlgeschlagen", "Bitte erneut versuchen", {
          position: 'tm',
          animate: 'slide',
          duration: 3000
        });
        console.log(response);
      }
  } else {
    tata.warn("Du bist offline", "Bitte später versuchen", {
      position: 'tm',
      animate: 'slide',
      duration: 3000
    });
  }
}

// _____________________________________________________________________________

const triggerFileClick = () => {
  document.getElementById("file").click();
}
// _____________________________________________________________________________


    const addNewProduct = () => {
      const form = document.getElementById("formFile");
      let inputVal = document.getElementsByClassName("gen-input")[0].value;

      if (inputVal.length > 20) {
        tata.warn("Produkt hinzufügen", "Maximal 20 Zeichen", {
          position: 'tm',
          animate: 'slide',
          duration: 3000
        });
      }
      else{
        var formdata = new FormData(form);
        formdata.append("Action", "addNewProduct");
        var xhr = new XMLHttpRequest();
    
        xhr.open("POST", "../../actions/cart_actions.php", false);
        xhr.send(formdata);
        
        result = xhr.responseText;
        console.log(formdata);
      
        if (result == 1) {
          loadCart();
          dismissModal();
          setTimeout(function () {
            openSearchList();
            let filterInputMobile = document.getElementById("filterInputMobile");
            filterInputMobile.value = inputVal;
            let event = new Event("keyup");
            filterInputMobile.dispatchEvent(event);
          }, 200);
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

 
    