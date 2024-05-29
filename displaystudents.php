<?php
include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

echo template("templates/partials/header.php");
echo template("templates/partials/nav.php");
?>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete the selected student records?");
}
</script>
<div class="container">
<div class="containerbanner">

<form method="post" action="delete_students.php" onsubmit="return confirmDelete()">
    <div class="container">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Select</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Student ID</th>
                       <th style="display:none" scope="col">Password</th> 
                        <th scope="col">Date of Birth</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">House</th>
                        <th scope="col">Town</th>
                        <th scope="col">County</th>
                        <th scope="col">Country</th>
                        <th scope="col">Postcode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Prepare the SQL statement
                    $sql = "SELECT * FROM student";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><input type="checkbox" name="selected_students[]" value="' . htmlspecialchars($row['studentid']) . '"></td>';

                            // Check if image data is not null before encoding
                            if ($row['image_data'] !== null) {
                                echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['image_data']) . '" alt="Student Photo" style="width: 80px; height: auto;border:50px;border-radius:50px"></td>';
                            } else {
                                echo '<td>No Photo</td>';
                            }

                            echo '<td>' . htmlspecialchars($row['studentid']) . '</td>';
                            echo '<td style="display:none" >' . htmlspecialchars($row['password']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['dob']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['firstname']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['lastname']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['house']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['town']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['county']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['country']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['postcode']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="12">No student records found.</td></tr>';
                    }

                    // Close statement and connection
                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-danger" name="delete_students">Delete Selected Students</button>
    </div>
</form>
</div>

</div>
<?php 
echo template("templates/partials/footer.php");
?>
