<?php
 include("_includes/config.inc");
 include("_includes/dbconnect.inc");
 include("_includes/functions.inc");
// Check if the delete button was clicked and if student IDs were selected
if (isset($_POST['delete_students']) && isset($_POST['selected_students'])) {
    $selected_students = $_POST['selected_students'];

    // Perform deletion operation
    foreach ($selected_students as $student_id) {
        $sql = "DELETE FROM student WHERE studentid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
    }

    echo "Selected student records deleted successfully.";
    header("Location: displaystudents.php");
} else {
    // Redirect back to students.php if no student IDs were selected
    header("Location: displaystudents.php");
    exit;
}

// Close database connection
$conn->close();
?>
