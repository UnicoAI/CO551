<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    echo template("templates/partials/header.php");
    echo template("templates/partials/nav.php");

    // Variable to hold result messages
    $resultMessage = "";

    // If a module has been selected
    if (isset($_POST['selmodule'])) {
        // Check if the entry already exists
        $existingSql = "SELECT * FROM studentmodules WHERE studentid = ? AND modulecode = ?";
        $existingStmt = mysqli_prepare($conn, $existingSql);
        mysqli_stmt_bind_param($existingStmt, "ss", $_SESSION['id'], $_POST['selmodule']);
        mysqli_stmt_execute($existingStmt);
        $existingResult = mysqli_stmt_get_result($existingStmt);

        if (mysqli_num_rows($existingResult) > 0) {
            // Entry already exists, display an error message
            $resultMessage .= "<p>The module " . $_POST['selmodule'] . " has already been assigned to you</p>";
        } else {
            // Insert the new entry
            $insertSql = "INSERT INTO studentmodules (studentid, modulecode) VALUES (?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param($insertStmt, "ss", $_SESSION['id'], $_POST['selmodule']);
            if (mysqli_stmt_execute($insertStmt)) {
                $resultMessage .= "<p>The module " . $_POST['selmodule'] . " has been assigned to you</p>";
            } else {
                $resultMessage .= "<p>Error assigning module</p>";
            }
        }
    } else {
        // If a module has not been selected, display the form
        $sql = "SELECT * FROM module";
        $result = mysqli_query($conn, $sql);
        $resultMessage .= "<div class='containerbanner'>";
        $resultMessage .= "<div class='form-signin'>";
        $resultMessage .= "<form name='frmassignmodule' action='' method='post' class='mt-3'>";
        $resultMessage .= "<div class='form-group'>";
        $resultMessage .= "<label for='selmodule'>Select a module to assign:</label>";
        $resultMessage .= "<select name='selmodule' class='form-control'>";
        // Display the module names in a dropdown selection box
        while ($row = mysqli_fetch_array($result)) {
            $resultMessage .= "<option value='$row[modulecode]'>$row[name]</option>";
        }
        $resultMessage .= "</select>";
        $resultMessage .= "</div>";
        $resultMessage .= "<button type='submit' name='confirm' class='btn btn-primary'>Save</button>";
        $resultMessage .= "</form>";
        $resultMessage .= "</div>";
        $resultMessage .= "</div>";
    }

    // Display result messages within a container
    echo "<div class='container'><div class='containerbanner'>
    " . $resultMessage . "</div></div>";

    // render the template
    echo template("templates/default.php", $data);
} else {
    header("Location: index.php");
}

echo template("templates/partials/footer.php");

?>
