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
$sql = "SELECT * FROM departments INNER JOIN students INNER JOIN seats INNER JOIN seating_arrangements INNER JOIN teachers ON departments.id = students.department_id AND seats.student_id = students.id AND seats.seating_arrangement_id = seating_arrangements.id AND seating_arrangements.teacher_id = teachers.id ORDER BY seating_arrangements.department_id ASC";
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
   <?php
   include "teacher_navbar.php";
   ?>

    <div class="container-fluid mt-4">
        <h2>Report</h2>
        <a href="print.php" class="btn btn-danger">Print</a>
        

        <!-- Display departments in a table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Reg Number</th>
                    <th>Department</th>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Session</th>
                    <th>Seat Number</th>
                    <th>Exam Date</th>
                    <th>Exam Time</th>
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
                        echo "<td>" . $row['exam_name'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['sessions'] . "</td>";
                        echo "<td>" . $row['set_number'] . "</td>";
                        echo "<td>" . $row['exam_date'] . "</td>";
                        echo "<td>" . $row['start_time'] ."-".$row['end_time']."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No Report found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

   

   
    <!-- JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>