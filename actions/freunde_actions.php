<?php

include("./include.php");
session_start();
$user_id = $_SESSION['id'];

/* Submitted variables */
if (isset($_POST['Action'])) {
    $Action = $_POST['Action'];
}
if (isset($_POST['friend_id'])) {
    $friend_id = $_POST['friend_id'];
    $friend_id = trim($friend_id);
    $friend_id = mysqli_real_escape_string($db, $friend_id);
}
if (isset($_POST['friends_table_id'])) {
    $friends_table_id = $_POST['friends_table_id'];
    $friends_table_id = trim($friends_table_id);
    $friends_table_id = mysqli_real_escape_string($db, $friends_table_id);
}
if (isset($_POST['friend_key'])) {
    $friend_key = $_POST['friend_key'];
    $friend_key = trim($friend_key);
    $friend_key = mysqli_real_escape_string($db, $friend_key);
}
if (isset($_POST['toggle_value'])) {
    $toggle_value = $_POST['toggle_value'];
    $toggle_value = trim($toggle_value);
    $toggle_value = mysqli_real_escape_string($db, $toggle_value);
}
if (isset($_POST['friend_rights_id'])) {
    $friend_rights_id = $_POST['friend_rights_id'];
    $friend_rights_id = trim($friend_rights_id);
    $friend_rights_id = mysqli_real_escape_string($db, $friend_rights_id);
}
if (isset($_POST['is_shared'])) {
    $is_shared = $_POST['is_shared'];
    $is_shared = trim($is_shared);
    $is_shared = mysqli_real_escape_string($db, $is_shared);
}
if (isset($_POST['handler_description'])) {
    $handler_description = $_POST['handler_description'];
    $handler_description = trim($handler_description);
    $handler_description = mysqli_real_escape_string($db, $handler_description);
}
// ________________________________________________________________________________

function loadFreunde()
{
    global $db;
    global $user_id;
    $content = "";
    $anfragen_content = "";
    $friend_counter = 0;

    $getFreundschaftAnfragen = "SELECT *, r.id as reg_id, f.id as friends_table_id from friends f join registration r on r.id = sender_id or r.id = recipient_id where recipient_id = $user_id group by r.id;";
    $getFreundschaftAnfragen_q = mysqli_query($db, $getFreundschaftAnfragen);

    if (mysqli_num_rows($getFreundschaftAnfragen_q) > 0) {
        $friend_counter++;
        $anfragen_content_counter = 0;
        $anfragen_content = "<span>Freundschaftsanfragen: </span>";

        while ($fetchAnfragen = mysqli_fetch_array($getFreundschaftAnfragen_q)) {
            $freund_name = $fetchAnfragen["DCvorname"];
            $friend_id = $fetchAnfragen["reg_id"];
            $status_id = $fetchAnfragen["status_id"];
            if ($friend_id == $user_id) {
                continue;
            }
            if ($status_id != 2) {
                continue;
            }
            $anfragen_content_counter++;
            $freund_image = $fetchAnfragen["profile_image"];
            $img_check_url = $_SERVER['SERVER_NAME'] . "assets/img/profile_images/" . $freund_image;
            if (@getimagesize($img_check_url)) {
            } else {
                $img_check_url = "../../assets/img/profile_images/avatar.png";
            }
            $friends_table_id = $fetchAnfragen["friends_table_id"];
            $anfragen_content .= "
            <div class='friend-wrapper friend-request-wrapper'>
                <img class='friend-profile-image' src='$img_check_url'>
                <p>$freund_name</p>
                <div class='friend-edit-icon'>
                    <img onclick='acceptFriend($friends_table_id)' class='icon small' src='../../assets/icons/success.svg'>
                    <img onclick='handleDeleteFriend($friends_table_id, \"$freund_name\")' class='icon small' src='../../assets/icons/delete.svg'>
                </div>
            </div>
        ";
        }
        $anfragen_content .= "<div class='divider'></div><span>Freunde:</span>";
        if ($anfragen_content_counter == 0) {
            $anfragen_content = "";
        }
    }

    $getFreunde = "SELECT *, r.id as reg_id, f.id as friends_table_id, f.status_id as status_id from friends f join registration r on r.id = sender_id or r.id = recipient_id where sender_id = $user_id or recipient_id = $user_id group by r.id;";
    $getFreunde_q = mysqli_query($db, $getFreunde);

    $search_input = "<div class='gen-input' type='text' placeholder='Kontakte suchen ...' onclick='handleSearchFriend()' ><p>Freunde suchen ...</p></div>";
    $content = "<div class='wrapper'>";
    $content = $content . $anfragen_content;

    while ($fetchFriends = mysqli_fetch_array($getFreunde_q)) {
        $freund_name = $fetchFriends["DCvorname"];
        $friend_id = $fetchFriends["id"];
        $friends_table_id = $fetchFriends["friends_table_id"];
        $freund_image = $fetchFriends["profile_image"];
        $img_check_url = $_SERVER['SERVER_NAME'] . "assets/img/profile_images/" . $freund_image;
        if (@getimagesize($img_check_url)) {
        } else {
            $img_check_url = "../../assets/img/profile_images/avatar.png";
        }
        $status_id = $fetchFriends["status_id"];

        if ($friend_id == $user_id) {
            continue;
        }
        if ($status_id > 1) {
            continue;
        }
        $friend_counter++;

        $content .= "
                    <div class='friend-wrapper'>
                        <img class='friend-profile-image' src='$img_check_url'>
                        <p>$freund_name</p>
                        <div class='friend-edit-icon'>
                            <img onclick='handleEditFriend($friend_id)' class='icon small' src='../../assets/icons/edit.svg'>
                            <img onclick='handleDeleteFriend($friends_table_id, \"$freund_name\")' class='icon small' src='../../assets/icons/delete.svg'>
                        </div>
                    </div>
                ";
    }
    $content .= "</div>";

    if ($friend_counter == 0) {
        $content = "
        <div class='no-content-wrapper'>
            <p>Noch keine Freunde in deiner Liste</p>
            <img class='icon no-content-icon' src='../../assets/icons/error.svg'>
        </div>
        ";
    }

    echo $search_input . $content;
}

// ________________________________________________________________________________

function deleteFriend($friends_table_id)
{
    global $db;
    global $user_id;

    $deleteFriend = "DELETE from friends 
    where id = $friends_table_id";
    $deleteFriend_q = mysqli_query($db, $deleteFriend);

    $error = mysqli_error($db);

    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}

// ________________________________________________________________________________

function searchFriend($friend_key)
{

    global $db;
    global $user_id;

    $getFriend = "select id, DCvorname, profile_image, identifier 
    from registration 
    where identifier = '$friend_key'";

    $getFriend_q = mysqli_query($db, $getFriend);
    $result_friends = mysqli_num_rows($getFriend_q);

    $error = mysqli_error($db);

    if ($error) {
        echo "error";
    } else {
        if ($result_friends > 0) {
            $fetchFriend = mysqli_fetch_assoc($getFriend_q);
            $result_as_friend_id = $fetchFriend["id"];
            $result_as_friend_name = $fetchFriend["DCvorname"];
            $profile_image = $fetchFriend["profile_image"];
            $identifier = $fetchFriend["identifier"];

            $getFriendsStatus = "SELECT status_id, id
            from friends f
            where f.`sender_id` = $user_id and f.recipient_id = $result_as_friend_id
            or f.`sender_id` = $result_as_friend_id and f.recipient_id = $user_id";
            $getFriendsStatus_q = mysqli_query($db, $getFriendsStatus);
            $fetch_status = mysqli_fetch_assoc($getFriendsStatus_q);
            $status = $fetch_status['status_id'];
            $friends_table_id = $fetch_status['id'];

            if ($status != 4) {

                if ($status == 1) {
                    $icon = "success.svg";
                    $onclick_action = "";
                } elseif ($status == 2) {
                    $icon = "pending.svg";
                    $onclick_action = "";
                } elseif ($status == "") {
                    $icon = "add.svg";
                    $onclick_action = "addFriend($result_as_friend_id)";
                } else {
                    $icon = "pending.svg";
                    $onclick_action = "";
                }


                if ($identifier != "" && $identifier != "null" && $identifier != null) {
                    if ($result_as_friend_id != $user_id) {
                        echo "
                        <div class='friend-wrapper'>
                            <img class='friend-profile-image' src='../../assets/img/profile_images/$profile_image'>
                            <p>$result_as_friend_name</p>
                            <div class='friend-edit-icon add-margin-right'>
                                <img id='addFriendIcon' onclick='$onclick_action' class='icon small' src='../../assets/icons/$icon'>
                            </div>
                        </div>
                        ";
                    }
                }
            }
        } else {
            echo "<span>Keine Ergebnisse für $friend_key</span>";
        }
    }
}

// ________________________________________________________________________________

function addFriend($friend_id)
{
    global $db;
    global $user_id;

    $insert_first_right = "INSERT INTO `friend_rights` (`sender_id`, `recipient_id`, `is_shared`, `sender_shares`, `recipient_shares`)
    VALUES
        ($user_id, $friend_id, 1, 1, 0);
    ";
    $insert_first_right_q = mysqli_query($db, $insert_first_right);

    $insert_new_friend = "INSERT INTO `friends` (`sender_id`, `recipient_id`, `status_id`)
    VALUES
        ($user_id, $friend_id, 2)
    ";
    $insert_new_friend_q = mysqli_query($db, $insert_new_friend);
    $error = mysqli_error($db);

    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}

// ________________________________________________________________________________

function acceptFriend($friends_table_id)
{
    global $db;
    global $friend_id;

    $accept_new_friend = "UPDATE friends set status_id = 1 where id = $friends_table_id";
    $accept_new_friend_q = mysqli_query($db, $accept_new_friend);

    $error = mysqli_error($db);
    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}

// ________________________________________________________________________________

function manageRights($friend_id)
{
    global $db;
    global $user_id;
    $content = "";

    $get_share_status = "SELECT * from friend_rights where sender_id = $user_id and recipient_id = $friend_id OR sender_id = $friend_id and recipient_id = $user_id";
    $get_share_status_q = mysqli_query($db, $get_share_status);
    $get_share_status_rows = mysqli_num_rows($get_share_status_q);

    $fetch_status = mysqli_fetch_assoc($get_share_status_q);

    $friend_rights_id = $fetch_status["id"];
    $sender_id = $fetch_status["sender_id"];
    $recipient_id = $fetch_status["recipient_id"];

    $sender_shares = $fetch_status["sender_shares"];
    $recipient_shares = $fetch_status["recipient_shares"];

    $is_checked = "checked";
    $is_disabled = "";
    $onclick_handler = "";
    $is_shared_warning = "(Warte auf Bestätigung durch Kontakt)";

    if ($sender_shares == 0 && $user_id == $sender_id && $recipient_shares == 0) {
        $onclick_handler = "updateRight($friend_rights_id, \"sender_id\", $sender_shares)";
        $is_checked = "";
        $is_shared_warning = "";
    } else if ($sender_shares == 0 && $user_id == $recipient_id && $recipient_shares == 0) {
        $onclick_handler = "updateRight($friend_rights_id, \"recipient_id\", $sender_shares)";
        $is_checked = "";
        $is_shared_warning = "";
    } else if ($sender_shares == 1 && $user_id == $recipient_id && $recipient_shares == 0) {
        $onclick_handler = "updateRight($friend_rights_id, \"recipient_id\", $sender_shares)";
        $is_checked = "";
        $is_shared_warning = "";
    } else if ($sender_shares == 1 && $user_id == $sender_id && $recipient_shares == 0) {
        $onclick_handler = "null";
        $is_checked = "";
        $is_disabled = "disabled";
        $is_shared_warning = "(Warte auf Bestätigung durch Kontakt)";
    } else if ($sender_shares == 0 && $user_id == $sender_id && $recipient_shares == 1) {
        $onclick_handler = "updateRight($friend_rights_id, \"sender_id\", $sender_shares)";
        $is_checked = "";
        $is_shared_warning = "";
    } else if ($sender_shares == 1 && $user_id == $sender_id && $recipient_shares == 1) {
        $onclick_handler = "updateRight($friend_rights_id, \"sender_id\", $sender_shares)";
        $is_checked = "checked";
        $is_shared_warning = "";
    } else if ($sender_shares == 1 && $user_id == $recipient_id && $recipient_shares == 1) {
        $onclick_handler = "updateRight($friend_rights_id, \"recipient_id\", $sender_shares)";
        $is_checked = "checked";
        $is_shared_warning = "";
    } else {
        $onclick_handler = "null";
        $is_checked = "";
        $is_disabled = "disabled";
        $is_shared_warning = "(Warte auf Bestätigung durch Kontakt)";
    }


    $content = "
                <table style='width: 100%; margin-top: 50px'>
                    <form id='form_rights'>
                    <tr>
                        <td><span>Liste teilen $is_shared_warning</span></td>
                        <td align='right'>
                            <label class='switch'>
                            <input name='rights[]' onclick='$onclick_handler' id='inputVal' type='checkbox' $is_checked $is_disabled value='$friend_rights_id'>
                            <span class='slider round'></span>
                        </td>   
                        </label>
                    </tr>
                </form>
                </table>
                <button class='btn btn-gen' onclick='dismissModal()' >Schließen</button>
                ";
    echo $content;
}

// ________________________________________________________________________________

function updateRight($friend_rights_id, $handler_description, $is_shared)
{
    global $db;
    global $user_id;

    $get_share_status = "select * from friend_rights where $handler_description = $user_id and is_shared = 1";
    $get_share_status_q = mysqli_query($db, $get_share_status);

    $fetch_status = mysqli_fetch_assoc($get_share_status_q);

    $sender_id = $fetch_status["sender_id"];
    $recipient_id = $fetch_status["recipient_id"];

    $sender_shares = $fetch_status["sender_shares"];
    $recipient_shares = $fetch_status["recipient_shares"];

    $handler = "";

    if ($recipient_id == $user_id) {
        $query_add_on = "recipient_shares";
        $handler = $recipient_shares;
    }
    if ($sender_id == $user_id) {
        $query_add_on = "sender_shares";
        $handler = $sender_shares;
    }

    $reset_current_shares = "UPDATE friend_rights SET $query_add_on = 0 where recipient_id = $user_id or sender_id = $user_id";
    $reset_current_shares_q = mysqli_query($db, $reset_current_shares);

    if ($handler == 1) {
        $update_share_status = "UPDATE friend_rights set $query_add_on = 0 where id = $friend_rights_id";
    } else {
        $update_share_status = "UPDATE friend_rights set $query_add_on = 1 where id = $friend_rights_id";
    }
    $update_share_status_q = mysqli_query($db, $update_share_status);

    $error = mysqli_error($db);
    if ($error) {
        echo "error";
    } else {
        echo 1;
    }
}

// ________________________________________________________________________________
/* Which action has been triggered? */

if ($Action == "loadFreunde") {
    loadFreunde();
}
if ($Action == "deleteFriend") {
    deleteFriend($friends_table_id);
}
if ($Action == "addFriend") {
    addFriend($friend_id);
}
if ($Action == "manageRights") {
    manageRights($friend_id);
}
if ($Action == "searchFriend") {
    searchFriend($friend_key);
}
if ($Action == "acceptFriend") {
    acceptFriend($friends_table_id);
}
if ($Action == "updateRight") {
    updateRight($friend_rights_id, $handler_description, $is_shared);
}

/* End */

mysqli_close($db);
