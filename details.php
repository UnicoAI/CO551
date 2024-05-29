<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");


// check logged in
if (isset($_SESSION['id'])) {

    echo template("templates/partials/header.php");
    echo template("templates/partials/nav.php");

    // if the form has been submitted
    if (isset($_POST['submit'])) {

        // build an sql statement to update the student details
        $sql = "UPDATE student SET firstname = ?, lastname = ?, house = ?, town = ?, county = ?, country = ?, postcode = ? WHERE studentid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $_POST['txtfirstname'], $_POST['txtlastname'], $_POST['txthouse'], $_POST['txttown'], $_POST['txtcounty'], $_POST['txtcountry'], $_POST['txtpostcode'], $_SESSION['id']);
        mysqli_stmt_execute($stmt);

        $data['content'] = "<div class='container'><div class='containerbanner'><p>Your details have been updated</p></div></div>";

    } else {
        // Build a SQL statement to return the student record with the id that
        // matches that of the session variable.
        $sql = "SELECT * FROM student WHERE studentid=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);

        // Define photoHtml variable to store HTML for displaying photo
        $photoHtml = '';
        if (!empty($row['image_data'])) {
            $photoHtml = '<img src="data:image/jpeg;base64,' . base64_encode($row['image_data']) . '" class="img-fluid" style="border:50px;border-radius:50px">';
        } else {
            $photoHtml = 'No photo available';
        }

        // using <<<EOD notation to allow building of a multi-line string
        $data['content'] = <<<EOD
        <div class="containerbanner">
                <div class="form-signin">
                <!-- Display the photo -->
           
                $photoHtml
           <h2>My Details</h2>
        <form name="frmdetails" action="" method="post" class="mt-3">
            <div class="form-group">
                <label for="txtfirstname">First Name:</label>
                <input name="txtfirstname" type="text" class="form-control" value="{$row['firstname']}" />
            </div>
            <div class="form-group">
                <label for="txtlastname">Surname:</label>
                <input name="txtlastname" type="text" class="form-control" value="{$row['lastname']}" />
            </div>
            <div class="form-group">
                <label for="txthouse">Number and Street:</label>
                <input name="txthouse" type="text" class="form-control" value="{$row['house']}" />
            </div>
            <div class="form-group">
                <label for="txttown">Town:</label>
                <input name="txttown" type="text" class="form-control" value="{$row['town']}" />
            </div>
            <div class="form-group">
                <label for="txtcounty">County:</label>
                <input name="txtcounty" type="text" class="form-control" value="{$row['county']}" />
            </div>
            <div class="form-group">
                <label for="txtcountry">Country:</label>
                <input name="txtcountry" type="text" class="form-control" value="{$row['country']}" />
            </div>
            <div class="form-group">
                <label for="txtpostcode">Postcode:</label>
                <input name="txtpostcode" type="text" class="form-control" value="{$row['postcode']}" />
            </div>
            
            <button type="submit" class="btn btn-primary" name="submit">Save</button>
        </form>
        </div>
        </div>
    EOD;
    }

    // render the template
    echo template("templates/default.php", $data);

} else {
    header("Location: index.php");
}

echo template("templates/partials/footer.php");

?>
