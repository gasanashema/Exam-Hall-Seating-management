<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: student-login.php");
    exit();
}

// MySQL connection
require_once "db_connection.php";

// Fetch current student's information
$currentStudentSql = "SELECT name, department_id, year, reg_no,session FROM students WHERE reg_no = '{$_SESSION['reg_no']}'";
$currentStudentResult = $conn->query($currentStudentSql);
if ($currentStudentResult->num_rows > 0) {
    $currentStudentInfo = $currentStudentResult->fetch_assoc();
} else {
    // Handle error if student information is not found
}

if (isset($_POST['book'])) {
    $exam_id = $_POST['exam_id'];
    $student_id = $_SESSION['student_id'];
    $set_number = mysqli_query($conn,"SELECT * from seats where student_id='$_SESSION[student_id]' AND seating_arrangement_id='$exam_id' LIMIT 1");
    $set_number_found = mysqli_fetch_assoc($set_number);
    if ($set_number_found == Null) {


    $sql = "SELECT * FROM  seating_details where seating_arrangement_id = '$exam_id'";
    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results) > 0) {
        $row = mysqli_fetch_assoc($results); 
        $number_of_seats_available = $row['num_students'];

        $number_of_booked_seats = $row['total_booked_seats'];

        $left_col = $row['remaining_on_left'];
        $middle_col = $row['remaining_in_middle'];
        $right_col = $row['remaining_on_right'];

        $reached_left = $row['reached_left'];
        $reached_right = $row['reached_right'];
        $reached_middle = $row['reached_middle'];
        $seat = Null;

        if($number_of_booked_seats<$number_of_seats_available){
            
            if ($left_col > 0) {
               $seat = "L". $reached_left;
               $set_seat = mysqli_query($conn, "INSERT INTO `seats`(`student_id`, `set_number`, `seating_arrangement_id`) VALUES ('$student_id', '$seat', '$exam_id')");

                if ($set_seat) {
                    $left_col -= 1;
                    $reached_left += 1;
                    $number_of_booked_seats += 1;
                   $update_col_count=mysqli_query($conn, "UPDATE `seating_details` SET `remaining_on_left`='$left_col',`total_booked_seats`='$number_of_booked_seats',`reached_left`='$reached_left' WHERE seating_arrangement_id = '$exam_id'");
                }

            }else if ($middle_col >= $left_col && $middle_col != 0) {
                $seat = "M". $reached_middle;
                $set_seat = mysqli_query($conn, "INSERT INTO `seats`(`id`, `student_id`, `set_number`, `seating_arrangement_id`) VALUES ('','$student_id','$seat','$exam_id')");

                if ($set_seat) {
                    $middle_col -= 1;
                    $reached_middle += 1;
                    $number_of_booked_seats += 1;
                    $update_col_count = mysqli_query($conn, "UPDATE `seating_details` SET `remaining_in_middle`='$middle_col',`total_booked_seats`='$number_of_booked_seats',`reached_middle`='$reached_middle' WHERE seating_arrangement_id = '$exam_id'");
                }
            }else if ($right_col > 0) {
               $seat = "R". $reached_right;
                $set_seat = mysqli_query($conn, "INSERT INTO `seats`(`id`, `student_id`, `set_number`, `seating_arrangement_id`) VALUES ('','$student_id','$seat','$exam_id')");

                if ($set_seat) {
                    $right_col -= 1;
                    $reached_right += 1;
                    $number_of_booked_seats += 1;
                    $update_col_count = mysqli_query($conn, "UPDATE `seating_details` SET `remaining_on_right`='$right_col',`total_booked_seats`='$number_of_booked_seats',`reached_right`='$reached_right' WHERE seating_arrangement_id = '$exam_id'");
                }
            }
        }else{
            echo "<script>alert('All Seats Booked')</script>";
        }
            // $sql2 = "SELECT count(*) as count_num from seats where seating_arrangement_id = '$exam_id' and room_id = '{$row['room_id']}'";
            // $res = mysqli_query($conn, $sql2);
            // if ($res) {
            //     $row_count = mysqli_fetch_assoc($res);
            //     $count_number = $row_count['count_num'];
            //     if ($count_number < $row['num_students']) {
            //         //allowed to book seat in this room

            //         echo "Count: " . $count_number . "  = " . $row['num_students'];
            //         break;
            //     }
            // } else {
            //     echo "Error executing query: " . mysqli_error($conn);
            // }
        
    } else {
        echo "No seating details found for this exam.";
    }
   }else{
    echo "<script>alert('You Have Already Booked Your Seat');</script>";
   }
}

// Update student information if form is submitted
if (isset($_POST['edit_user'])) {
    $studentName = $_POST['studentName'];
    $departmentId = $_POST['department'];
    $year = $_POST['year'];
    $session = $_POST['session'];

    // Update student's name, department, and year in the database
    $updateStudentSql = "UPDATE students SET name = '$studentName', department_id = '$departmentId', year = '$year',session='$session' WHERE reg_no = '{$_SESSION['reg_no']}'";
    if ($conn->query($updateStudentSql) === TRUE) {
        // Update session username if name is updated
        $_SESSION['username'] = $studentName;
        // Redirect to same page after updating student information
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        // Handle error if update query fails
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch all departments
$departmentsSql = "SELECT * FROM departments";
$departmentsResult = $conn->query($departmentsSql);

// Fetch incoming exams arrangements
$incomingExamsSql = "SELECT sa.exam_name, sa.exam_date,sa.id, sa.start_time, sa.end_time, t.username AS teacher_name
                     FROM seating_arrangements sa
                     LEFT JOIN teachers t ON sa.teacher_id = t.id
                     WHERE sa.exam_date >= CURDATE()
                     AND sa.year = '{$currentStudentInfo['year']}'
                     AND sa.department_id = '{$currentStudentInfo['department_id']}'
                     AND FIND_IN_SET('{$currentStudentInfo['session']}', sa.sessions)";

$incomingExamsResult = $conn->query($incomingExamsSql);
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

        .status-active {
            color: green;
        }

        .status-pending {
            color: orange;
        }

        .status-expired {
            color: red;
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container ">
            <div class="w-full " style="width: 100%;">
                <div class="d-flex w-full justify-content-end">
                    <div class="d-flex">
                        <!-- Username and Logout Button -->
                        <span class="navbar-brand"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ""; ?></span>
                        <a href="logout-student.php" class="nav-link text-danger">Logout</a>

                    </div>
                </div>
                <div class="w-full d-flex" style="position: relative; top: -8px;">
                    <!-- Navbar Brand and Toggle Button -->
                    <a class="navbar-brand" href="#">Exam Seating Arrangement</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Navbar Links -->
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#editStudentModal">Account</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Incoming Exams</h2>
        <div class="row">
            <?php
            if ($incomingExamsResult->num_rows > 0) {
                while ($examRow = $incomingExamsResult->fetch_assoc()) {
                    echo "<div class='col-md-6 mb-3'>
                        <div class='card'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$examRow['exam_name']}</h5>
                                <p class='card-text'>Teacher: {$examRow['teacher_name']}</p>
                                <p class='card-text'>Date: {$examRow['exam_date']}</p>
                                <p class='card-text'>Time: {$examRow['start_time']} - {$examRow['end_time']}</p>
                                ";
                                $set_number = mysqli_query($conn,"SELECT set_number from seats where student_id='$_SESSION[student_id]' AND seating_arrangement_id='$examRow[id]' LIMIT 1");
                                $set_number_found = mysqli_fetch_assoc($set_number);
                                if ($set_number_found != Null) {
                                   echo "<p class='card-text'>Your Seat: {$set_number_found['set_number']}</p>";
                                }
                                ?>

                                <?php
                                echo "
                                <form action='' method='POST' >
                                <input class='d-none' name='exam_id' value='{$examRow['id']}' >
                                <button type='submit' name='book' class='btn btn-primary'>Book Seat</a>
                                </form>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<div class='col-md-12'>No incoming exams found</div>";
            }
            ?>
        </div>
    </div>


    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Your account info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Student Form -->
                    <form id="editStudentForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="regNo">Registration Number</label>
                            <input type="text" class="form-control" id="regNo" name="regNo" value="<?php echo $currentStudentInfo['reg_no']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="studentName">Student Name</label>
                            <input type="text" required class="form-control" id="studentName" name="studentName" value="<?php echo $currentStudentInfo['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select required class="form-control" id="department" name="department">
                                <?php
                                if ($departmentsResult->num_rows > 0) {
                                    while ($deptRow = $departmentsResult->fetch_assoc()) {
                                        echo "<option value='" . $deptRow['id'] . "'";
                                        if ($deptRow['id'] == $currentStudentInfo['department_id']) {
                                            echo " selected";
                                        }
                                        echo ">" . $deptRow['department_name'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No departments found</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year</label>
                            <select required class="form-control" id="year" name="year">
                                <option value="I" <?php if ($currentStudentInfo['year'] == 'I') echo 'selected'; ?>>I</option>
                                <option value="II" <?php if ($currentStudentInfo['year'] == 'II') echo 'selected'; ?>>II</option>
                                <option value="III" <?php if ($currentStudentInfo['year'] == 'III') echo 'selected'; ?>>III</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select session:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" <?php if ($currentStudentInfo['session'] == "Day") echo "checked" ?> required type="radio" id="day_session" name="session" value="Day">
                                <label class="form-check-label" for="day_session">Day Session</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" <?php if ($currentStudentInfo['session'] == "Night") echo "checked" ?> required type="radio" id="night_session" name="session" value="Night">
                                <label class="form-check-label" for="night_session">Night Session</label>
                            </div>
                        </div>
                        <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>