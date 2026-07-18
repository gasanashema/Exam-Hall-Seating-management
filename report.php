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
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
   <?php
   include "admin_navbar.php";
   ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">Seating Report</h2>
            <a href="print.php" class="btn btn-danger">🖨️ Print Report</a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Reg Number</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Session</th>
                        <th>Seat</th>
                        <th>Exam Date</th>
                        <th>Time Slot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['reg_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['department_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['exam_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['sessions']) . "</td>";
                            echo "<td><span class='badge bg-primary text-white'>" . htmlspecialchars($row['set_number']) . "</span></td>";
                            echo "<td>" . htmlspecialchars($row['exam_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['start_time']) . " - " . htmlspecialchars($row['end_time']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center text-muted'>No reports found.</td></tr>";
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