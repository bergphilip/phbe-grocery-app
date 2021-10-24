document.getElementById("mainContent").onscroll = function() {scrollFunction()};

// ________________________________________________________________________

function scrollFunction() {
  if (document.getElementById("mainContent").scrollTop > 40 || document.documentElement.scrollTop > 40) {
    document.getElementsByClassName("topbar")[0].classList.add("minified");
    document.getElementsByClassName("topbar-font")[0].classList.add("minified-font");
    document.getElementsByClassName("input")[0].classList.add("input-minified");

  } else {
    document.getElementsByClassName("topbar")[0].classList.remove("minified");
    document.getElementsByClassName("topbar-font")[0].classList.remove("minified-font");
    document.getElementsByClassName("input")[0].classList.remove("input-minified");
  }
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
      if (xhr.readyState == 4) {
        hideSplash();  
      }
      
    }, 10)
}

// ________________________________________________________________________

function checkIfInCart(){
  
  let productCount = document.getElementsByClassName("count-provider");
  let productsArr = JSON.parse(localStorage.getItem('products'));
  if (productsArr !== null) {
    let product_name = "";

    for (let i = 0; i <= productCount.length - 1; i++) {
  
      product_name = productCount[i].value;
  
      if(productsArr.includes(product_name)){

        product_arr = product_name.split("%");
        product_id = product_arr[1];

        addCartClass(product_id);

        let isInCart = document.getElementById(`productCount${product_id}`);
        isInCart.value = 1;
      }
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
}

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

    // var filtered = Object.filter(groupArr, score => score > 1);
    // console.log(filtered);

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

  modalHeader.innerHTML = "Einkaufsliste";
  modalDismissButton.style.display = "none";
  modalConfirmButton.addEventListener("click", () =>{
    dismissModal();
  });

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
      <label onclick="removeFromCart( ${product_id}, '${productDefault}', 0, 1)">
        <input type='checkbox' style='display: none'>
        <span>${product}</span>                        
      </label>
      `;
      groupWrap.appendChild(ckButton);
    }
  }
  else{
      modal.innerHTML = `
      <span class='no-products'>
          noch keine Produkte in der Liste
      </span>`;
    }
}


function searchProduct() {
  var div, input, filter, ul, li, a, i, txtValue;
  input = document.getElementById("filterInput");
  filter = input.value.toUpperCase();

  let mainContentWrapper = document.getElementById("mainContent");
  let productWrapper = mainContentWrapper.getElementsByClassName("product-wrapper");

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
          
      } else {
          productWrapper[i].style.display = "none";
      }
  }
}

function openSearchList(){
  openModal(500, "80%");
  let modal = document.getElementById("modalBody");
  let modalHeaderFont = document.getElementById("modalHeaderFont");
  let modalConfirmButton = document.getElementById("modalConfirmButton");
  let modalDismissButton = document.getElementById("modalDismissButton");

  modalHeaderFont.innerHTML = `<input type="text" placeholder="Suchen ..." id="filterInputMobile" class="input-mobile" onkeyup="searchProductMobile()" />`;

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
        <img class='product-image' src='${img_url}' onerror=this.src='../../assets/img/products/cart_black.svg';>
        <p class='product-name'>${product_name}</p>
    </div>`;
  }

  modal.innerHTML = content;
  checkIfInCart();
  document.getElementById("filterInputMobile").focus();
}

function searchProductMobile() {
  var div, input, filter, ul, li, a, i, txtValue;
  input = document.getElementById("filterInputMobile");
  filter = input.value.toUpperCase();

  let mainContentWrapper = document.getElementById("modalBody");
  let productWrapper = mainContentWrapper.getElementsByClassName("product-wrapper");
 
  for (i = 0; i < productWrapper.length; i++) {
      productName = productWrapper[i].getElementsByClassName("product-name")[0];
      txtValue = productName.textContent || productName.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
          productWrapper[i].style.display = "";
          
      } else {
          productWrapper[i].style.display = "none";
      }
  }
}