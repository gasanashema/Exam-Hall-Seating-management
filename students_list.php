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
$sql = "SELECT * FROM students INNER JOIN departments ON students.department_id = departments.id";
$result = $conn->query($sql);


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
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Exam Seating Arrangement</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="departments.php">Departments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="arrangement.php">Seating Arrangement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="students_list.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="created_arrangements.php">Arrangements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="exam_rooms.php">Exam Rooms</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Departments</h2>


        <!-- Display departments in a table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Registration Number</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Session</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['reg_no'] . "</td>";
                        echo "<td>" . $row['department_name'] . "</td>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "<td>" . $row['session'] . "</td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No Students found</td></tr>";
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