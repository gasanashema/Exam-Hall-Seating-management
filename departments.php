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
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #2c3e50;
            height: 70px;

        }

        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
        }

        .modal-dialog {
            max-width: 500px;
        }
    </style>
</head>

<body>
   <?php
   include "admin_navbar.php";
   ?>

    <div class="container mt-4">
        <h2>Departments</h2>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDepartmentModal">Add Department</button>

        <!-- Display departments in a table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Date of School</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['department_name'] . "</td>";
                        echo "<td>" . $row['department_description'] . "</td>";
                        echo "<td>" . $row['date_of_school'] . "</td>";
                        echo "<td>
                                <button type='button' class='btn btn-sm btn-primary' data-toggle='modal' data-target='#editDepartmentModal" . $row['id'] . "'>Edit</button>
                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#deleteDepartmentModal" . $row['id'] . "'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No departments found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for adding department -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!-- Modal content -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModalLabel">Add Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="department_name">Department Name:</label>
                            <input type="text" class="form-control" id="department_name" name="department_name" required>
                        </div>
                        <div class="form-group">
                            <label for="department_description">Description:</label>
                            <textarea class="form-control" id="department_description" name="department_description" rows="3"></textarea>
                        </div>
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_department">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals for editing and deleting department -->
    <?php
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
            <!-- Edit Department Modal -->
            <div class="modal fade" id="editDepartmentModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!-- Modal content -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="department_id" value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <label for="edit_department_name">Department Name:</label>
                                    <input type="text" class="form-control" id="edit_department_name" name="edit_department_name" value="<?php echo $row['department_name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_department_description">Description:</label>
                                    <textarea class="form-control" id="edit_department_description" name="edit_department_description" rows="3"><?php echo $row['department_description']; ?></textarea>
                                </div>
                                <?php if (isset($error)) { ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="edit_department">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Department Modal -->
            <div class="modal fade" id="deleteDepartmentModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteDepartmentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!-- Modal content -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteDepartmentModalLabel">Delete Department</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="delete_department_id" value="<?php echo $row['id']; ?>">
                                <p>Are you sure you want to delete department '<?php echo $row['department_name']; ?>'?</p>
                                <?php if (isset($error)) { ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger" name="delete_department">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    <?php
        }
    }
    ?>

    <!-- JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>