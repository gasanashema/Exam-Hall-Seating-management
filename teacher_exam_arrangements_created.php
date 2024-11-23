<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// MySQL connection
require_once "db_connection.php";

// Fetch seating arrangements with related information
$sql = "SELECT sa.id, sa.created_at,sa.year, sa.exam_name, sa.exam_date, sa.start_time, sa.end_time,
               t.username AS teacher_name, d.department_name
        FROM seating_arrangements sa
        LEFT JOIN teachers t ON sa.teacher_id = t.id
        LEFT JOIN departments d ON sa.department_id = d.id WHERE sa.teacher_id='$_SESSION[teacher_id]'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Seating Arrangements</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #2c3e50;
            height: 80px;
        }

        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
        }

        .active {
            font-weight: bold;
            color: #17a2b8 !important;
        }

        .status-active {
            color: green;
        }

        .status-pending {
            color: orange;
        }

        .status-expired {
            color: red;
        }
    </style>
</head>

<body>
    <?php
    include 'teacher_navbar.php';
    ?>


    <div class="container mt-4">
        <h2>Your Seating Arrangements</h2>


        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Exam Name</th>
                    <th>Teacher Name</th>
                    <th>Department Name</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Calculate status based on exam date, start time, and end time
                        $status = "Pending";
                        $current_datetime = date("Y-m-d H:i:s");
                        $exam_datetime = $row['exam_date'] . " " . $row['start_time'];

                        if ($current_datetime > $exam_datetime) {
                            $status = "Expired";
                        } elseif ($current_datetime >= $row['exam_date'] && $current_datetime <= $exam_datetime) {
                            $status = "Active";
                        }

                        // Apply CSS class based on status
                        $status_class = "";
                        switch ($status) {
                            case "Active":
                                $status_class = "status-active";
                                break;
                            case "Pending":
                                $status_class = "status-pending";
                                break;
                            case "Expired":
                                $status_class = "status-expired";
                                break;
                            default:
                                $status_class = "";
                                break;
                        }

                        echo "<tr>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>" . $row['exam_name'] . "</td>";
                        echo "<td>" . $row['teacher_name'] . "</td>";
                        echo "<td>" . $row['department_name'] . "</td>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "<td class='" . $status_class . "'>" . $status . "</td>";
                        echo "<td>
                               ";
                ?>
                        <button data-toggle="modal" data-target="#editStudentModal" class="btn btn-success">View Sitting Plan</button>
                        <!-- Edit Student Modal -->
                        <div class="modal fade col-lg-12" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editStudentModalLabel">Edit Your account info</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- card-->
                                            <div class='card col-md-4'>
                                                <div class='card-body'>
                                                    <h5 class='card-title'>Left Column</h5>

                                                    <?php
                                                    // Assuming $row['id'] is already fetched and safe to use
                                                    $seatingArrangementId = mysqli_real_escape_string($conn, $row['id']);
                                                    $sql = "SELECT * FROM seats INNER JOIN students ON seats.student_id = students.id WHERE seating_arrangement_id = '$seatingArrangementId' AND set_number LIKE 'L%' ORDER BY set_number ASC";

                                                    $result_data = mysqli_query($conn, $sql);
                                                    if ($result_data) {
                                                        $k = 1;
                                                        while ($students = mysqli_fetch_assoc($result_data)) {
                                                    ?>
                                                            <p class='card-text'><?php echo $k++ . ". " . htmlspecialchars($students['reg_no']) . " " . $students['name'] . " (" . $students['set_number'] . ")"; ?></p>
                                                    <?php
                                                        }
                                                    } else {
                                                        echo "Error: " . mysqli_error($conn);
                                                    }
                                                    ?>



                                                </div>
                                            </div>

                                            <!-- card-->
                                            <div class='card col-md-4'>
                                                <div class='card-body'>
                                                    <h5 class='card-title'>Middle Column</h5>
                                                    <?php
                                                    // Assuming $row['id'] is already fetched and safe to use
                                                    $seatingArrangementId = mysqli_real_escape_string($conn, $row['id']);
                                                    $sql = "SELECT * FROM seats INNER JOIN students ON seats.student_id = students.id WHERE seating_arrangement_id = '$seatingArrangementId' AND set_number LIKE 'M%' ORDER BY set_number ASC";

                                                    $result_data = mysqli_query($conn, $sql);
                                                    if ($result_data) {
                                                        $k = 1;
                                                        while ($students = mysqli_fetch_assoc($result_data)) {
                                                    ?>
                                                            <p class='card-text'><?php echo $k++ . ". " . htmlspecialchars($students['reg_no']) . " " . $students['name'] . " (" . $students['set_number'] . ")"; ?></p>
                                                    <?php
                                                        }
                                                    } else {
                                                        echo "Error: " . mysqli_error($conn);
                                                    }
                                                    ?>




                                                </div>
                                            </div>

                                            <!-- card-->
                                            <div class='card col-md-4'>
                                                <div class='card-body'>
                                                    <h5 class='card-title'>Right Column</h5>
                                                    <?php
                                                    // Assuming $row['id'] is already fetched and safe to use
                                                    $seatingArrangementId = mysqli_real_escape_string($conn, $row['id']);
                                                    $sql = "SELECT * FROM seats INNER JOIN students ON seats.student_id = students.id WHERE seating_arrangement_id = '$seatingArrangementId' AND set_number LIKE 'R%' ORDER BY set_number ASC";

                                                    $result_data = mysqli_query($conn, $sql);
                                                    if ($result_data) {
                                                        $k = 1;
                                                        while ($students = mysqli_fetch_assoc($result_data)) {
                                                    ?>
                                                            <p class='card-text'><?php echo $k++ . ". " . htmlspecialchars($students['reg_no']) . " " . $students['name'] . " (" . $students['set_number'] . ")"; ?></p>
                                                    <?php
                                                        }
                                                    } else {
                                                        echo "Error: " . mysqli_error($conn);
                                                    }
                                                    ?>




                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                <?php
                        echo "
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No seating arrangements found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>