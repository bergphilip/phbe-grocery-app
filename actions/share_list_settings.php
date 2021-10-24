<?php

$get_share_status = "select * from friend_rights where sender_id = $user_id or recipient_id = $user_id and is_shared = 1";
$get_share_status_q = mysqli_query($db, $get_share_status);
$nums = mysqli_num_rows($get_share_status_q);
if ($nums > 0) {

    $fetch_status = mysqli_fetch_assoc($get_share_status_q);
    $friend_rights_id = $fetch_status["id"];

    $recipient_id = $fetch_status["recipient_id"];
    $sender_id = $fetch_status["sender_id"];

    $recipient_shares = $fetch_status["recipient_shares"];
    $sender_shares = $fetch_status["sender_shares"];

    if ($recipient_shares == 1 && $sender_shares == 1) {
        if ($recipient_id == $user_id) {
            $user_id = $sender_id;
        }
    } else {
        $user_id = $user_id;
    }
} else {
    $user_id = $user_id;
}
