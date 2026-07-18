<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// MySQL connection
require_once "db_connection.php";

// Fetch departments
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);

// Check if department form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_department"])) {
        $department_name = $_POST["department_name"];
        $department_description = $_POST["department_description"];

        // Check if department name is unique
        $check_sql = "SELECT * FROM departments WHERE department_name = '$department_name'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $error = "Department name already exists.";
        } else {
            // Insert department
            $insert_sql = "INSERT INTO departments (department_name, department_description) VALUES ('$department_name', '$department_description')";
            if ($conn->query($insert_sql) === TRUE) {
                header("Location: departments.php");
                exit();
            } else {
                $error = "Error occurred while adding department.";
            }
        }
    } elseif (isset($_POST["edit_department"])) {
        $department_id = $_POST["department_id"];
        $department_name = $_POST["edit_department_name"];
        $department_description = $_POST["edit_department_description"];

        // Update department
        $update_sql = "UPDATE departments SET department_name = '$department_name', department_description = '$department_description' WHERE id = $department_id";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: departments.php");
            exit();
        } else {
            $error = "Error occurred while updating department.";
        }
    } elseif (isset($_POST["delete_department"])) {
        $department_id = $_POST["delete_department_id"];

        // Delete department
        $delete_sql = "DELETE FROM departments WHERE id = $department_id";
        if ($conn->query($delete_sql) === TRUE) {
            header("Location: departments.php");
            exit();
        } else {
            $error = "Error occurred while deleting department.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <?php
    include 'teacher_navbar.php';
    ?>

    <div class="container mt-5">
        <h2 class="page-title mb-4">Departments</h2>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Description</th>
                        <th>Date of School</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><strong>" . htmlspecialchars($row['department_name']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['department_description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date_of_school']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center text-muted'>No departments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>





    <!-- JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>