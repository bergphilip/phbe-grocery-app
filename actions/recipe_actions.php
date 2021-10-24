<?php
include("./include.php");

session_start();
$user_id = $_SESSION['id'];

/* Submitted variables */
if (isset($_POST['Action'])) {
    $Action = $_POST['Action'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $user_id = trim($user_id);
    $user_id = mysqli_real_escape_string($db, $user_id);
}
if (isset($_POST['recipe_id'])) {
    $recipe_id = $_POST['recipe_id'];
    $recipe_id = trim($recipe_id);
    $recipe_id = mysqli_real_escape_string($db, $recipe_id);
}
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_id = trim($product_id);
    $product_id = mysqli_real_escape_string($db, $product_id);
}
if (isset($_POST['step_id'])) {
    $step_id = $_POST['step_id'];
    $step_id = trim($step_id);
    $step_id = mysqli_real_escape_string($db, $step_id);
}
if (isset($_POST['step'])) {
    $step = $_POST['step'];
    $step = trim($step);
    $step = mysqli_real_escape_string($db, $step);
}
// __________________________________
// User Id festlegen, falls Liste geteilt wird

include("./share_list_settings.php");

if (isset($_POST['recipe_id'])) {
    $recipe_id = $_POST['recipe_id'];
    $recipe_id = trim($recipe_id);
    $recipe_id = mysqli_real_escape_string($db, $recipe_id);
}
if (isset($_POST['newName'])) {
    $newName = $_POST['newName'];
    $newName = trim($newName);
    $newName = mysqli_real_escape_string($db, $newName);
}
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_id = trim($product_id);
    $product_id = mysqli_real_escape_string($db, $product_id);
}
if (isset($_POST['content'])) {
    $content = $_POST['content'];
    $content = trim($content);
    $content = mysqli_real_escape_string($db, $content);
}
if (isset($_POST['cooking_id'])) {
    $cooking_id = $_POST['cooking_id'];
    $cooking_id = trim($cooking_id);
    $cooking_id = mysqli_real_escape_string($db, $cooking_id);
}
if (isset($_POST['is_new'])) {
    $is_new = $_POST['is_new'];
    $is_new = trim($is_new);
    $is_new = mysqli_real_escape_string($db, $is_new);
}

// ____________________________________________________________
function loadRecipes()
{
    global $db;
    global $user_id;

    $content = "";

    $getMealCategory = "SELECT mc.id,meal FROM meal_category mc
        join recipes r on mc.id = r.meal_category_id 
        where r.user_id = $user_id GROUP BY mc.id;";
    $getMealCategory_q = mysqli_query($db, $getMealCategory);

    while ($mealRow = mysqli_fetch_array($getMealCategory_q)) {
        $cat = $mealRow['meal'];
        $cat_id = $mealRow['id'];

        $content .= "<div class='group-name'>$cat</div>";

        $getRecipes = "SELECT *, r.id as rec_id from recipes r
            join meal_category m on m.id = r.meal_category_id 
            where r.user_id = $user_id AND meal_category_id = $cat_id;";
        $getRecipes_q = mysqli_query($db, $getRecipes);
        $nums = mysqli_num_rows($getRecipes_q);


        if ($nums > 0) {
            while ($recipeRow = mysqli_fetch_array($getRecipes_q)) {
                $name = $recipeRow['name'];
                $rec_id = $recipeRow['rec_id'];
                $meal_cat = $recipeRow['meal'];
                $img_url = $recipeRow['img_url'];
                $img_url = "../../assets/img/recipe_uploads/" . $img_url;
                if ($img_url == "img/recipes/cooking.svg") {
                    $style = "recipe-img default-recipe-img";
                } else {
                    $style = "recipe-img";
                }

                $content .= "
                   <div class='recipe-wrapper' onclick='openRecipe($rec_id)'>
                    <img class='$style' src='$img_url'>
                   <div class='recipe-name'>$name</div>
                  </div>
                  ";
            }
        } else {
            continue;
        }
    }
    echo $content;
}
// ____________________________________________________________
function loadEditRecipe($user_id, $recipe_id)
{
    global $db;

    $content = "";
    $getRecipe = "SELECT r.name, r.img_url, r.id from recipes r
    join meal_category m on m.id = r.meal_category_id 
    where r.user_id = $user_id and r.id = $recipe_id;";
    $getRecipe_q = mysqli_query($db, $getRecipe);
    $recipeRow = mysqli_fetch_array($getRecipe_q);
    $name = $recipeRow['name'];

    $getGrouping = "SELECT * from grouping";
    $getGrouping_q = mysqli_query($db, $getGrouping);
    $fetchNum = mysqli_num_rows($getGrouping_q);

    $content = "
               <div class='edit-recipe-name-wrapper'>
                <input id='editRecipeName' class='edit-recipe-input' onkeyup='showSave()' type='text' value='$name'>
                <img src='./img/icons/save.svg' id='saveIcon' class='edit-recipe-confirm' onclick='saveNewName($user_id, $recipe_id)'>
                <p class='err-message' id='errMsg'></p>
               </div>";

    $content .= "<div class='edit-recipe-ingredient-wrapper'>";

    for ($i = 1; $i <= $fetchNum; $i++) {
        $fetchGroups = mysqli_fetch_assoc($getGrouping_q);
        $group = $fetchGroups['group'];
        $getIngredients = "SELECT *, p.id as product_id, i.id as ingr_id, i.recipe_id as recipe_id from product p
        left join ingredients i on i.ingredient_id = p.id
        where p.group_id = $i
        group by p.product asc
        ";
        $getIngredients_q = mysqli_query($db, $getIngredients);
        $nums = mysqli_num_rows($getIngredients_q);

        if ($nums > 0) {
            $content .= "<div class='edit-recipe-group-header'>$group</div>";
            while ($fetchResults = mysqli_fetch_array($getIngredients_q)) {
                $ingredient = $fetchResults['product'];
                $ingr_id = $fetchResults['ingr_id'];
                $rec_id = $fetchResults['recipe_id'];
                $product_id = $fetchResults['product_id'];
                $custom_user_id = $fetchResults['custom_user_id'];

                if ($custom_user_id == NULL || $custom_user_id == $user_id) {
                } else {
                    continue;
                }

                if ($recipe_id == NULL) {
                    $ingredient_style = "edit-recipe-ingredient not-active-ingredient";
                } elseif ($recipe_id == $rec_id) {
                    $ingredient_style = "edit-recipe-ingredient active-ingredient";
                } else {
                    $ingredient_style = "edit-recipe-ingredient not-active-ingredient";
                }

                $content .= "<div id='ingredientBox$product_id' onclick='changeIngredient($user_id, $product_id, $recipe_id)' class='$ingredient_style'>$ingredient</div>";
            }
        }
    }
    $content .= "<br><p class='delete-recipe' onclick='deleteRecipe($user_id, $recipe_id)'>Rezept löschen</p>";
    $content .= "</div>";
    echo $content;
}

// ______________________________________________________________________________

function saveNewName($recipe_id, $newName)
{
    global $db;

    $updateName = "UPDATE recipes SET name = '$newName' WHERE id = $recipe_id";
    $updateName_q = mysqli_query($db, $updateName);

    echo 1;
}

// ______________________________________________________________________________

function changeIngredient($product_id, $user_id, $recipe_id)
{
    global $db;

    $changeIngredient = "SELECT id FROM ingredients WHERE user_id = $user_id AND recipe_id = $recipe_id AND
    ingredient_id = $product_id;
    ";
    $changeIngredient_q = mysqli_query($db, $changeIngredient);
    $nums = mysqli_num_rows($changeIngredient_q);

    if ($nums > 0) {
        $deleteIngredient = "DELETE FROM ingredients WHERE recipe_id = $recipe_id AND user_id = $user_id AND ingredient_id = $product_id";
        $deleteIngredient_q = mysqli_query($db, $deleteIngredient);

        echo 0;
    } else {
        $insertNewIngredient = "INSERT INTO `ingredients` (`user_id`, `recipe_id`, `ingredient_id`)
        VALUES
            ($user_id, $recipe_id, $product_id);
        ";
        $insertNewIngredient_q = mysqli_query($db, $insertNewIngredient);

        echo 1;
    }
}

// ____________________________________________________________

function saveEditCooking($cooking_id, $user_id, $content, $is_new, $recipe_id)
{
    global $db;

    if ($is_new == 1) {
        $insertContent = "INSERT INTO `cooking` (`user_id`, `recipe_id`, `content`)
        VALUES
            ($user_id, $recipe_id, '$content');
        ";
        $insertContent_q = mysqli_query($db, $insertContent);
        echo $insertContent;
    } else {
        $updateContent = "UPDATE cooking SET content = '$content' WHERE id = $cooking_id and user_id = $user_id";
        $updateContent_q = mysqli_query($db, $updateContent);
        echo $updateContent;
    }

    echo 1;
}

// ____________________________________________________________


function loadZubereitung($recipe_id)
{
    global $db;
    global $user_id;

    $content = "";

    $getRecipe = "select img_url, name from recipes where id = $recipe_id";
    $getRecipe_q = mysqli_query($db, $getRecipe);

    $fetchRecipe = mysqli_fetch_array($getRecipe_q);
    $img_url = $fetchRecipe['img_url'];
    $img_url = "../../assets/img/recipe_uploads/" . $img_url;
    $recipe_name = $fetchRecipe['name'];

    $content = "<input id='recipe_id' type='hidden' value='$recipe_id'> ";

    $content .= "
        <div class='recipe-image-wrapper'>
            <img src='$img_url' class='zubereitung-recipe-image'>
            <p class='recipe-name-zubereitung'>$recipe_name</p>
        </div>
    ";

    $getIngredients = "SELECT p.product as product_name, p.img_url, p.id as product_id, i.recipe_id, g.id as group_id from product p 
    join ingredients i on i.ingredient_id = p.id
    join grouping g on p.group_id = g.id
    where i.recipe_id = $recipe_id
    order by p.product asc;
    ";
    $getIngredients_q = mysqli_query($db, $getIngredients);
    $ingredients_num_rows = mysqli_num_rows($getIngredients_q);

    $content .= "
    <div class='ingredients-wrapper'>
        <div class='static-description'>
        <span>Zutaten</span>
        <button class='btn btn-gen edit-zubereitung-btn' onclick='editIngredients($recipe_id)'>Bearbeiten</button>
    </div>
    ";


    if ($ingredients_num_rows > 0) {
        while ($fetchIngredient = mysqli_fetch_array($getIngredients_q)) {

            $product_name = $fetchIngredient['product_name'];
            $product_id = $fetchIngredient['product_id'];
            $i = $fetchIngredient['group_id'];
            $img_url = $fetchIngredient['img_url'];
            $img_url = "../../assets/" . $img_url;
            $img_check_url = $_SERVER['SERVER_NAME'] . "assets/" . $img_url;
            if (@getimagesize($img_check_url)) {
            } else {
                $img_url = "../../assets/img/products/cart_black.png";
            }

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
        $content .= "
        <div class='no-content-wrapper'>
            <p>Keine Zutaten ausgewählt</p>
            <img class='icon no-content-icon' src='../../assets/icons/error.svg'>
        </div>
        ";
    }

    $getCookingSteps = "SELECT * from cooking where recipe_id = $recipe_id and user_id = $user_id";
    $getCookingSteps_q = mysqli_query($db, $getCookingSteps);
    $steps_num_rows = mysqli_num_rows($getCookingSteps_q);

    if ($steps_num_rows > 0) {

        $content .= "
        <div class='static-description'>
        <span>Zubereitung</span>
        <button class='btn btn-gen edit-zubereitung-btn' onclick='handleEditCookingStepsPress()'>Bearbeiten</button>
        <button class='btn btn-gen edit-zubereitung-btn add-step-btn ' onclick='handleAddNewStep($recipe_id)'>+</button>
        ";

        $content .= "<div class='steps-wrapper'>";
        $step_counter = 0;

        while ($fetchSteps = mysqli_fetch_array($getCookingSteps_q)) {
            $step_counter++;
            $step = $fetchSteps["content"];
            $step_id = $fetchSteps["id"];

            $content .= "
            <div class='step-counter'>$step_counter</div>
                 <span class='edit-cooking-steps-span'>
                    <img onclick='editStep($step_id)' class='icon small' src='../../assets/icons/edit.svg'>
                    <img onclick='handleDeleteStep($step_id)' class='icon small' src='../../assets/icons/delete.svg'>
                </span>
            <div class='step-individual-wrapper'>
                <div id='step$step_id' class='step'>$step</div>
            </div>
        ";
        }

        $content .= "</div>";
        $content .= "<button onclick='handleDeleteRecipe($recipe_id)' class='btn btn-gen delete-recipe-btn'>Dieses Rezept löschen</button>";

        echo $content . "</div>";
    } else {
        $content .= "
        <div class='static-description'>
        <span>Zubereitung</span>
        <button class='btn btn-gen edit-zubereitung-btn add-step-btn ' onclick='handleAddNewStep($recipe_id)'>+</button>
        ";

        $content .= "
        
        <div class='no-content-wrapper'>
            <p>Keine Zutaten ausgewählt</p>
            <img class='icon no-content-icon' src='../../assets/icons/error.svg'>
        </div>
        ";
        $content .= "<button onclick='handleDeleteRecipe($recipe_id)' class='btn btn-gen delete-recipe-btn'>Dieses Rezept löschen</button>";

        echo $content;
    }
}

// ____________________________________________________________

function loadIngredients($recipe_id)
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

        $content .= "<div class='group-name group-name-zubereitung'>$groupName</div>";

        $getGroceries = "SELECT p.product, p.img_url, p.group_id, p.is_custom, p.custom_user_id, p.id, g.group
        from product p
        left join grouping g on g.id = p.group_id
        where p.group_id = $i 
        group by product";

        $getGroceries_q = mysqli_query($db, $getGroceries);
        $nums = mysqli_num_rows($getGroceries_q);

        if ($nums > 0) {

            while ($groRow = mysqli_fetch_array($getGroceries_q)) {
                $product_id = $groRow['id'];
                $check_if_ingredient_in_cart = "SELECT ingredient_id from ingredients where ingredient_id = $product_id and user_id = $user_id and recipe_id = $recipe_id group by ingredient_id";
                $check_if_ingredient_in_cart_q = mysqli_query($db, $check_if_ingredient_in_cart);
                $fetch_ingredient = mysqli_fetch_assoc($check_if_ingredient_in_cart_q);
                $is_ingredient = $fetch_ingredient["ingredient_id"];
                $ingredient_user_id = $groRow['user_id'];
                if ($is_ingredient == null) {
                    $product_overlay_is_active_style = "";
                } else {
                    $product_overlay_is_active_style = "is-in-cart";
                }
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
                    <div class='product-wrapper' onclick='handleIngredientClick($product_id, $recipe_id)'>
                    <div class='product-overlay $product_overlay_is_active_style' id='product$product_id'>
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

// ____________________________________________________________

function handleIngredient($product_id, $recipe_id)
{

    global $db;
    global $user_id;
    $getCurrentIngredientState = "SELECT count(id) as countIng from ingredients where ingredient_id = $product_id and recipe_id = $recipe_id and user_id = $user_id";
    $getCurrentIngredientState_q = mysqli_query($db, $getCurrentIngredientState);
    $fetch = mysqli_fetch_assoc($getCurrentIngredientState_q);
    $numIngredients = $fetch["countIng"];

    if ($numIngredients == 0) {
        $insertIngredient = "INSERT INTO `ingredients` (`user_id`, `recipe_id`, `ingredient_id`)
        VALUES
            ($user_id, $recipe_id, $product_id);
        ";
        $insertIngredient_q = mysqli_query($db, $insertIngredient);
        $error = mysqli_error($db, $insertIngredient_q);
        if ($error) {
            echo "error";
        } else {
            echo 1;
        }
    } else {
        $deleteIngredient = "DELETE FROM ingredients where recipe_id = $recipe_id and ingredient_id = $product_id and user_id = $user_id";
        $deleteIngredient_q = mysqli_query($db, $deleteIngredient);
        $error = mysqli_error($db, $deleteIngredient_q);
        if ($error) {
            echo "error";
        } else {
            echo 2;
        }
    }
}

// ____________________________________________________________

function saveStep($step_id, $step)
{
    global $db;
    global $user_id;

    $updateStep = "update cooking set content = '$step' where id = $step_id";
    $updateStep_q = mysqli_query($db, $updateStep);

    $error = mysqli_error($db, $updateStep_q);
    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}

// ____________________________________________________________

function deleteStep($step_id)
{
    global $db;
    global $user_id;

    $deleteStep = "delete from cooking where id = $step_id and user_id = $user_id";
    $deleteStep_q = mysqli_query($db, $deleteStep);

    $error = mysqli_error($db, $deleteStep_q);
    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}

// ____________________________________________________________

function addNewStep($step_id, $step)
{
    global $db;
    global $user_id;

    $addNewStep = "INSERT INTO `cooking` (`user_id`, `recipe_id`, `content`)
    VALUES
        ($user_id, $step_id, '$step');
    ";
    $addNewStep_q = mysqli_query($db, $addNewStep);

    $error = mysqli_error($db, $addNewStep_q);
    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}
// ____________________________________________________________

function addNewRecipe()
{
    global $db;
    global $user_id;

    $recipe_name = $_POST['recipe_name'];
    $meal_category_id = $_POST['meal_category_id'];

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
                $fileDest = "../assets/img/recipe_uploads/" . $fileNameNew;
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
    if ($fileName == "") {
        $path = "cooking.svg";
    } else {
        $path = "$fileNameNew";
    }
    $insert = "INSERT INTO `recipes` (`name`, `user_id`, `meal_category_id`, `img_url`)
    VALUES
        ('$recipe_name', $user_id, $meal_category_id, '$path')";
    $insert_query = mysqli_query($db, $insert);
}
// ________________________________________________________________________________
function deleteRecipe($recipe_id)
{
    global $db;
    global $user_id;

    $delete_recipe = "DELETE from recipes where id = $recipe_id and user_id = $user_id";
    $delete_recipe_q = mysqli_query($db, $delete_recipe);

    $delete_ingredients = "DELETE FROM ingredients WHERE recipe_id = $recipe_id";
    $delete_ingredients_q = mysqli_query($db, $delete_ingredients);

    $error = mysqli_error($db, $delete_recipe);
    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}
// ________________________________________________________________________________

/* Which action has been triggered? */
if ($Action == "loadRecipes") {
    loadRecipes();
}
if ($Action == "addNewRecipe") {
    addNewRecipe();
}
if ($Action == "loadEditRecipe") {
    loadEditRecipe($user_id, $recipe_id);
}
if ($Action == "saveNewName") {
    saveNewName($recipe_id, $newName);
}
if ($Action == "changeIngredient") {
    changeIngredient($product_id, $user_id, $recipe_id);
}
if ($Action == "deleteRecipe") {
    deleteRecipe($recipe_id);
}
if ($Action == "saveEditCooking") {
    saveEditCooking($cooking_id, $user_id, $content, $is_new, $recipe_id);
}
if ($Action == "loadZubereitung") {
    loadZubereitung($recipe_id);
}
if ($Action == "loadIngredients") {
    loadIngredients($recipe_id);
}
if ($Action == "handleIngredient") {
    handleIngredient($product_id, $recipe_id);
}
if ($Action == "saveStep") {
    saveStep($step_id, $step);
}
if ($Action == "deleteStep") {
    deleteStep($step_id);
}
if ($Action == "addNewStep") {
    addNewStep($step_id, $step);
}


/* End */

mysqli_close($db);
