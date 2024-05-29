<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");
include_once("password_hash.php");


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form fields
    $errors = [];

    if (empty($_POST["txtfirstname"])) {
        $errors[] = "First name is required";
    }

    // Validate Date of Birth
    $dob = $_POST["dob"];
    if (!empty($dob) && !validate_date($dob)) {
        $errors[] = "Date of Birth must be in the format YYYY-MM-DD";
    }

    // Add validation for other fields...

    // Check if password is provided
    if (empty($_POST["txtpassword"])) {
        $errors[] = "Password is required";
    }

    // Check if student ID is provided
    if (empty($_POST["txtstudentid"])) {
        $errors[] = "Student ID is required";
    } else {
        $studentid = $_POST["txtstudentid"];
        if (student_id_exists($studentid)) {
            $errors[] = "Student ID already exists";
        }
    }

    // Handle file upload
    if ($_FILES["fileToUpload"]["name"]) {
        $image_data = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);
    } else {
        $errors[] = "Image is required";
    }

    if (empty($errors)) {
        // Prepare SQL statement
        $sql = "INSERT INTO student (studentid, dob, firstname, lastname, house, town, county, country, postcode, password, image_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sssssssssss", $studentid, $dob, $firstname, $lastname, $house, $town, $county, $country, $postcode, $password, $image_data);

            // Set parameters
            $firstname = $_POST["txtfirstname"];
            $lastname = $_POST["txtlastname"];
            $house = $_POST["txthouse"];
            $town = $_POST["txttown"];
            $county = $_POST["txtcounty"];
            $country = $_POST["txtcountry"];
            $postcode = $_POST["txtpostcode"];
            $password = generate_hash($_POST["txtpassword"]);

            // Execute statement
            mysqli_stmt_execute($stmt);

            // Close statement
            mysqli_stmt_close($stmt);

            // Redirect to success page or display success message
            header("Location: success.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}

// Function to check if student ID already exists in the database
function student_id_exists($studentid) {
    global $conn;
    $sql = "SELECT * FROM student WHERE studentid = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num_rows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $num_rows > 0;
}

// Function to validate date format (YYYY-MM-DD)
function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

echo template("templates/partials/header.php");
echo template("templates/partials/nav.php");

// Display form
echo <<<EOD

<div class="container">
<div class="containerbanner">

<h2 class="mt-5">ADD STUDENT</h2>
<form name="frmaddstudent" method="post" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="txtstudentid">Student ID:</label>
        <input type="text" class="form-control" name="txtstudentid" required>
    </div>
    <div class="form-group">
        <label for="txtfirstname">First Name:</label>
        <input type="text" class="form-control" name="txtfirstname" required>
    </div>
    <div class="form-group">
        <label for="txtlastname">Last Name:</label>
        <input type="text" class="form-control" name="txtlastname">
    </div>
    <div class="form-group">
        <label for="dob">Date of Birth:</label>
        <input type="text" class="form-control" name="dob" placeholder="YYYY-MM-DD">
    </div>
    <div class="form-group">
        <label for="txthouse">House:</label>
        <input type="text" class="form-control" name="txthouse">
    </div>
    <div class="form-group">
        <label for="txttown">Town:</label>
        <input type="text" class="form-control" name="txttown">
    </div>
    <div class="form-group">
        <label for="txtcounty">County:</label>
        <input type="text" class="form-control" name="txtcounty">
    </div>
    <div class="form-group">
        <label for="txtcountry">Country:</label>
        <input type="text" class="form-control" name="txtcountry">
    </div>
    <div class="form-group">
        <label for="txtpostcode">Postcode:</label>
        <input type="text" class="form-control" name="txtpostcode">
    </div>
    <div class="form-group">
        <label for="txtpassword">Password:</label>
        <input type="password" class="form-control" name="txtpassword" required>
    </div>
    <div class="form-group">
        <label for="fileToUpload">Image:</label>
        <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

</div>
EOD;

echo template("templates/partials/footer.php");

?>
