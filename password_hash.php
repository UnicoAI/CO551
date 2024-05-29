<?php

include_once("_includes/functions.inc");

// Check if the "password" key exists in the $_GET array
if (isset($_GET["password"])) {
    $pass = $_GET["password"];
    echo password_hash($pass, PASSWORD_DEFAULT);
} else {
    //echo "Password parameter is missing.";
}


