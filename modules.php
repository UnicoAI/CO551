<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

// check logged in
if (isset($_SESSION['id'])) {

    echo template("templates/partials/header.php");
    echo template("templates/partials/nav.php");

    // Prepare SQL statement
    $sql = "SELECT * FROM studentmodules sm, module m WHERE m.modulecode = sm.modulecode AND sm.studentid = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the student ID parameter
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['id']);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Prepare page content
    $data['content'] .= "<div class='containerbanner'>";
 
    $data['content'] .= "<div class='table-responsive'>";
    $data['content'] .= "<table class='table table-bordered'>";
    $data['content'] .= "<thead class='thead-light'>";
    $data['content'] .= "<tr><th colspan='5' class='text-center'>Modules</th></tr>";
    $data['content'] .= "<tr><th>Code</th><th>Type</th><th>Level</th></tr>";
    $data['content'] .= "</thead>";
    $data['content'] .= "<tbody>";
    // Display the modules within the HTML table
    while ($row = mysqli_fetch_array($result)) {
        $data['content'] .= "<tr><td>" . htmlspecialchars($row['modulecode']) . "</td><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['level']) . "</td></tr>";
    }
    $data['content'] .= "</tbody>";
    $data['content'] .= "</table>";
    $data['content'] .= "</div>";
    $data['content'] .= "</div>";
    
    // Render the template
    echo template("templates/default.php", $data);

    // Close statement
    mysqli_stmt_close($stmt);

} else {
    header("Location: index.php");
}

echo template("templates/partials/footer.php");

?>
