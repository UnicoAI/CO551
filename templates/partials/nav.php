<?php

include("_includes/dbconnect.inc");

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    // Fetch the user's details including the photo
    $sql = "SELECT * FROM student WHERE studentid=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    // Define photoHtml variable to store HTML for displaying photo
    $photoHtml = '';
    if (!empty($row['image_data'])) {
        $photoHtml = '<img src="data:image/jpeg;base64,' . base64_encode($row['image_data']) . '" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">';
    }

    // Render the navigation bar with the user's photo
    echo <<<HTML
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
        <a class="nav-link" href="details.php">$photoHtml</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="modules.php">My Modules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="assignmodule.php">Assign Module</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="details.php">My Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addstudent.php">Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="displaystudents.php">Display Students</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                   
                </ul>
            </div>
        </div>
    </nav>
HTML;
} else {
    // If the user is not logged in, render a simple navigation bar
    echo <<<HTML
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Hello</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
HTML;
}

?>
