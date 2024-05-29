<?php
include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");
include_once("password_hash.php");
// Check if the user is logged in
if (isset($_SESSION['id'])) {
    echo template("templates/partials/header.php");
    echo template("templates/partials/nav.php");}
echo "<div class='container'>";
echo "<div class='containerbanner'>";
echo "Student added successfully!";
echo "</div>";
echo "</div>";

echo template("templates/partials/footer.php");

?>
