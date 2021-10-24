<?php
session_start();
$user_id = $_SESSION['id'];

include("./include.php");

// set user_id if shared
include("share_list_settings.php");

/* Submitted variables */
if (isset($_POST['Action'])) {
  $Action = $_POST['Action'];
}
if (isset($_POST['origin'])) {
  $origin = $_POST['origin'];
  $origin = trim($origin);
  $origin = mysqli_real_escape_string($db, $origin);
}
// __________________________________
if (isset($_POST['in_cart'])) {
  $in_cart = $_POST['in_cart'];
  $in_cart = trim($in_cart);
  $in_cart = mysqli_real_escape_string($db, $in_cart);
}
if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $product_id = trim($product_id);
  $product_id = mysqli_real_escape_string($db, $product_id);
}
if (isset($_POST['filter'])) {
  $filter = $_POST['filter'];
  $filter = trim($filter);
  $filter = mysqli_real_escape_string($db, $filter);
}
if (isset($_POST['sync_string'])) {
  $sync_string = $_POST['sync_string'];
  $sync_string = trim($sync_string);
  $sync_string = mysqli_real_escape_string($db, $sync_string);
}
// ________________________________________________________________________________
function loadCart()
{
  global $db;
  global $user_id;
  $content = "";

  $getGroupCount = "SELECT * FROM grouping";
  $getGroupCount_q = mysqli_query($db, $getGroupCount);
  $groupCount = mysqli_num_rows($getGroupCount_q);

  for ($i = 1; $i <= $groupCount; $i++) {
    $getGroups = "SELECT * FROM grouping WHERE id = $i";
    $getGroups_q = mysqli_query($db, $getGroups);
    $groupRow = mysqli_fetch_array($getGroups_q);
    $groupName = $groupRow['group'];

    $content .= "<div class='group-name'>$groupName</div>";

    $getGroceries = "SELECT p.product, p.img_url, p.group_id, p.is_custom, p.custom_user_id, p.id, g.group
      from product p
      left join grouping g on g.id = p.group_id 
      where p.group_id = $i";

    $getGroceries_q = mysqli_query($db, $getGroceries);
    $nums = mysqli_num_rows($getGroceries_q);

    if ($nums > 0) {
      while ($groRow = mysqli_fetch_array($getGroceries_q)) {
        $product_id = $groRow['id'];
        $product_name = $groRow['product'];
        $custom_user_id = $groRow['custom_user_id'];
        $is_custom = $groRow['is_custom'];
        if ($is_custom == 1 && $custom_user_id != $user_id) {
          continue;
        }
        $img_url = "";
        $img_url = $groRow['img_url'];
        $img_url = "../../assets/" . $img_url;
        $img_check_url = $_SERVER['SERVER_NAME'] . "assets/" . $img_url;
        if (@getimagesize($img_check_url)) {
        } else {
          $img_url = "../../assets/img/products/cart_black.png";
        }
        $group = $groRow['group'];

        $content .= "
            <div class='product-wrapper' onclick='handleProductClick($product_id, \"$product_name\", $i)'>
            <div class='product-overlay' id='product$product_id'>
              <img src='../../assets/icons/success.svg' class='product-icon'>
            </div>  
                <input type='hidden' value='0' id='productCount$product_id'/>
                <input class='count-provider' type='hidden' value='$product_name%$product_id%$i'/>
                <img class='product-image' src='$img_url' onerror=this.src='../../assets/img/products/cart_black.png';>
                <p class='product-name'>$product_name</p>
            </div>
          ";
      }
    } else {
      $content .= "<p>Keine Produkte für $groupName</p>";
    }
  }
  echo $content;
}
// ________________________________________________________________________________

function addProduct($product_id, $in_cart)
{
  global $db;
  global $user_id;

  if ($in_cart == 0) {
    $in_cart = 1;
  } else {
    $in_cart = 0;
  }

  $updateInCart = "UPDATE cart SET in_cart = $in_cart WHERE product_id = $product_id AND user_id = $user_id";
  $updateInCart_q = mysqli_query($db, $updateInCart);

  echo 1;
}
/* ------------------------------------------------------------------------------------ */
# Old function, now replaced by JS function with localstorage
function createList()
{

  global $db;
  global $user_id;

  $content = "<form type='hidden' id='selectForm'>";

  $getProducts = "SELECT in_cart FROM cart WHERE in_cart = 1 AND user_id = $user_id;";
  $q = mysqli_query($db, $getProducts);
  $nums = mysqli_num_rows($q);


  if ($nums > 0) {

    $getGroupCount = "SELECT * FROM grouping";
    $getGroupCount_q = mysqli_query($db, $getGroupCount);
    $groupCount = mysqli_num_rows($getGroupCount_q);


    for ($i = 1; $i <= $groupCount; $i++) {

      $createList = "SELECT *, c.id as cart_id FROM product p
          join cart c on c.product_id = p.id 
          join grouping g on p.group_id = g.id
          where c.user_id = $user_id 
          and g.id = $i
          and c.in_cart > 0
          order by product asc";
      $createList_query = mysqli_query($db, $createList);
      $numRows = mysqli_num_rows($createList_query);

      $getGroupName = mysqli_query($db, "SELECT grouping.group FROM grouping WHERE id = $i;");
      $row = mysqli_fetch_array($getGroupName);

      if ($numRows !== 0) {

        $groupName = $row['group'];

        $content .= "<div class='group-name'>$groupName</div>";

        while ($row = mysqli_fetch_array($createList_query)) {
          $name = $row['product'];
          $id = $row['cart_id'];
          $content .= "
                 <div id='ck-button'>
                  <label>
                    <input type='checkbox' name='select[]' value='$id' style='display: none'>
                     <span>$name</span>                        
                   <input type='hidden' name='user_id' value='$user_id'/>
                  </label>
                </div>
                ";
        }
      }
    }
    $content .= "</form>";

    $err = mysqli_error($db);
    if ($err) {
      $content = $err;
    }
  }
  if ($nums == 0) {
    $content = "<p class='no-products no-products__cart'>Noch keine Produkte ausgewählt</p>";
  }
  echo $content;
}
// ________________________________________________________________________________
function saveListChanges()
{
  global $db;
  global $user_id;

  $user_id = $_POST['user_id'];
  foreach ($_POST['select'] as $cart_id) {
    $saveChanges = "UPDATE cart SET in_cart = 0 WHERE id = $cart_id AND user_id = $user_id;";
    $saveChanges_query = mysqli_query($db, $saveChanges);
  }
}
// ________________________________________________________________________________
function initCart()
{
  global $db;
  global $user_id;

  $getState = "SELECT * FROM product WHERE custom_user_id = $user_id";
  $getState_q = mysqli_query($db, $getState);
  $stateCount = mysqli_num_rows($getState_q);

  $content = "";

  if ($stateCount > 0) {
    $getGroupCount = "SELECT * FROM grouping";
    $getGroupCount_q = mysqli_query($db, $getGroupCount);
    $groupCount = mysqli_num_rows($getGroupCount_q);

    for ($i = 1; $i <= $groupCount; $i++) {
      $getGroups = "SELECT * FROM grouping WHERE id = $i";
      $getGroups_q = mysqli_query($db, $getGroups);
      $groupRow = mysqli_fetch_array($getGroups_q);
      $groupName = $groupRow['group'];

      $getGroceries = "SELECT *, p.id as product_id FROM product p
       join grouping g on p.group_id = g.id
       where p.custom_user_id = $user_id and is_custom = 1 and g.id = $i
       order by product asc";
      $getGroceries_q = mysqli_query($db, $getGroceries);
      $nums = mysqli_num_rows($getGroceries_q);

      if ($nums > 0) {
        $content .= "<div class='group-name'>$groupName</div>";
        while ($groRow = mysqli_fetch_array($getGroceries_q)) {
          $product_id = $groRow['product_id'];
          $product_name = $groRow['product'];
          $img_url = $groRow['img_url'];

          $group = $groRow['group'];

          $content .= "
            <div class='product-wrapper'>
             <img class='product-image' src='$img_url'>
             <div class='product-banner' id='productBanner$product_id' onclick=''>
              <p class='product-name' style='position: relative;
              top: 10px;
              text-align: left;
              padding-left: 10px;'>$product_name<img class='add-product-icons' onclick='openDeleteProduct($product_id, $user_id)' src='img/icons/delete.svg'></p>
             </div>
           </div>
           ";
        }
      }
    }
  } else {
    $content = "<p class='no-products'>Noch keine Produkte ausgewählt</p>";
  }
  $content .= "
  <div class='add' onclick='openAddModal($user_id)'>
  </div>
  ";

  echo $content;
}
// ________________________________________________________________________________
function deleteProduct($product_id)
{
  global $db;
  global $user_id;

  $deleteProduct = "DELETE FROM product WHERE id = $product_id AND custom_user_id = $user_id";
  $deleteProduct_q = mysqli_query($db, $deleteProduct);

  $deleteCart = "DELETE FROM cart WHERE product_id = $product_id AND user_id = $user_id";
  $deleteCart_q = mysqli_query($db, $deleteCart);
}
// ________________________________________________________________________________
function fetchSyncItems()
{
  global $db;
  global $user_id;
  global $get_share_status;
  global $recipient_id;

  $getItems = "SELECT sync_string from ingredients_sync where user_id = $user_id";
  $getItems_q = mysqli_query($db, $getItems);

  $fetchSyncItems = mysqli_fetch_array($getItems_q);

  $items = $fetchSyncItems["sync_string"];
  if ($items == "") {
    $items = "[]";
  }

  $error = mysqli_error($db);

  if ($error) {
    echo 0;
  } else {
    echo $items;
  }
}
// ________________________________________________________________________________
function uploadSyncItems($sync_string)
{
  global $db;
  global $user_id;

  $deleteOldStrings = "DELETE FROM ingredients_sync where user_id = $user_id";
  $deleteOldStrings_q = mysqli_query($db, $deleteOldStrings);

  $insertNewString = "INSERT INTO `ingredients_sync` (`user_id`, `sync_string`)
  VALUES
    ($user_id, '$sync_string');
  ";
  $insertNewString_q = mysqli_query($db, $insertNewString);

  echo $insertNewString;
  echo "nice";
}
// ________________________________________________________________________________
function addNewProduct()
{
  global $db;
  global $user_id;

  $productName = $_POST['productName'];
  $productGroup = $_POST['productGroup'];

  echo 1;

  $file = $_FILES['file'];
  $fileName = $_FILES['file']['name'];

  if ($fileName !== "") {
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileErr = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActual = strtolower(end($fileExt));

    if ($fileErr === 0) {
      if ($fileSize < 500000000) {
        $fileNameNew = uniqid('', true) . "." . $fileActual;
        $fileDest = "../assets/img/products/custom/" . $fileNameNew;
        move_uploaded_file($fileTmp, $fileDest);
      } else {
        // File zu groß
        echo "§Datei zu groß";
      }
    } else {
      // Fehler Upload 
      echo "§Fehlerhafter Upload";
    }
  }
  if ($file !== "") {
    $path = "img/products/custom/" . $fileNameNew;
  } else {
    $path = "img/products/cart_black.png";
  }
  $insert = "INSERT INTO `product` (`product`, `img_url`, `group_id`, `is_custom`, `custom_user_id`)
  VALUES
    ('$productName', '$path', $productGroup, 1, $user_id);
  ";
  $insert_query = mysqli_query($db, $insert);
}
// ________________________________________________________________________________
/* Which action has been triggered? */

if ($Action == "loadCart") {
  loadCart();
}
if ($Action == "initCart") {
  initCart();
}
if ($Action == "addNewProduct") {
  addNewProduct();
}
if ($Action == "deleteProduct") {
  deleteProduct($product_id);
}
if ($Action == "fetchSyncItems") {
  fetchSyncItems();
}
if ($Action == "uploadSyncItems") {
  uploadSyncItems($sync_string);
}


/* End */

mysqli_close($db);
