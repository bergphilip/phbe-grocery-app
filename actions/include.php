<?php

date_default_timezone_set("Europe/Berlin");
$browser = $_SERVER['HTTP_USER_AGENT'];
$root = $_SERVER['DOCUMENT_ROOT'];

include("../server/connection/dbconnect.php");
include("../server/connection/https.php");
