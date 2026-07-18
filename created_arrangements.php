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
        LEFT JOIN departments d ON sa.department_id = d.id";
$result = $conn->query($sql);

// Pre-fetch all seats with student details to avoid N+1 queries in the loop
$all_seats = [];
$seats_sql = "SELECT s.*, st.reg_no, st.name FROM seats s INNER JOIN students st ON s.student_id = st.id ORDER BY s.set_number ASC";
$seats_result = $conn->query($seats_sql);
if ($seats_result) {
    while ($seat = $seats_result->fetch_assoc()) {
        $sa_id = $seat['seating_arrangement_id'];
        $col = substr($seat['set_number'], 0, 1); // 'L', 'M', or 'R'
        $all_seats[$sa_id][$col][] = $seat;
    }
}
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
    include 'admin_navbar.php';
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
                        <button data-toggle="modal" data-target="#editStudentModal<?php echo $row['id']; ?>" class="btn btn-success">View Sitting Plan</button>
                        <!-- Edit Student Modal -->
                        <div class="modal fade col-lg-12" id="editStudentModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editStudentModalLabel">View Seating Plan</h5>
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
                                                     $students_list = isset($all_seats[$row['id']]['L']) ? $all_seats[$row['id']]['L'] : [];
                                                     $k = 1;
                                                     foreach ($students_list as $students) {
                                                     ?>
                                                             <p class='card-text'><?php echo $k++ . ". " . htmlspecialchars($students['reg_no']) . " " . $students['name'] . " (" . $students['set_number'] . ")"; ?></p>
                                                     <?php
                                                     }
                                                     ?>



                                                 </div>
                                             </div>

                                             <!-- card-->
                                             <div class='card col-md-4'>
                                                 <div class='card-body'>
                                                     <h5 class='card-title'>Middle Column</h5>
                                                     <?php
                                                     $students_list = isset($all_seats[$row['id']]['M']) ? $all_seats[$row['id']]['M'] : [];
                                                     $k = 1;
                                                     foreach ($students_list as $students) {
                                                     ?>
                                                             <p class='card-text'><?php echo $k++ . ". " . htmlspecialchars($students['reg_no']) . " " . $students['name'] . " (" . $students['set_number'] . ")"; ?></p>
                                                     <?php
                                                     }
                                                     ?>




                                                 </div>
                                             </div>

                                             <!-- card-->
                                             <div class='card col-md-4'>
                                                 <div class='card-body'>
                                                     <h5 class='card-title'>Right Column</h5>
                                                     <?php
                                                     $students_list = isset($all_seats[$row['id']]['R']) ? $all_seats[$row['id']]['R'] : [];
                                                     $k = 1;
                                                     foreach ($students_list as $students) {
                                                     ?>
                                                             <p class='card-text'><?php echo $k++ . ". " . htmlspecialchars($students['reg_no']) . " " . $students['name'] . " (" . $students['set_number'] . ")"; ?></p>
                                                     <?php
                                                     }
                                                     ?>



                                                 </div>
                                             </div></div>
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