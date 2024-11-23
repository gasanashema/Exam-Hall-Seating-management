<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "db_connection.php";

// Fetch departments for dropdown
$department_sql = "SELECT * FROM departments";
$department_result = $conn->query($department_sql);

// Fetch teachers
$teacher_sql = "SELECT * FROM teachers WHERE id='$_SESSION[teacher_id]'";
$teachers_result = $conn->query($teacher_sql);

// Fetch exam rooms
$exam_room_sql = "SELECT * FROM exam_room";
$exam_rooms_results = $conn->query($exam_room_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["session"])) {
        $department_id = $_POST["department_id"];
        $year = $_POST["year"];
        $exam_name = $_POST["exam_name"];
        $sessions = implode(",", $_POST["session"]);
        $exam_date = $_POST["exam_date"];
        $start_time = $_POST["start_time"];
        $end_time = $_POST["end_time"];
        $room_ids = $_POST["room_id"];
        $teacher_id = $_POST["teacher_id"];

        // Insert into seating_arrangements table
        $insert_sql = "INSERT INTO seating_arrangements (department_id, year, exam_name, sessions, exam_date, start_time, end_time, teacher_id) VALUES ('$department_id', '$year', '$exam_name', '$sessions', '$exam_date', '$start_time', '$end_time', '$teacher_id')";

        if ($conn->query($insert_sql) === TRUE) {
            $seating_arrangement_id = $conn->insert_id;

            // Insert into seating_details table for each selected room
            foreach ($room_ids as $room_id) {
                $num_students = $_POST["num_students" . $room_id];
                $num_of_available_seats_in_column = floor($num_students/3);
                $extra_seats=$num_students%3;
                $left_col = $num_of_available_seats_in_column;
                $middle_col = $num_of_available_seats_in_column;
                $right_col = $num_of_available_seats_in_column;
                if ($extra_seats == 1) {
                    $left_col +=1;
                }
                if ($extra_seats == 2) {
                    $left_col += 1;
                    $middle_col += 1;
                }
                $insert_detail_sql = "INSERT INTO seating_details (seating_arrangement_id, room_id, num_students,remaining_on_left,remaining_in_middle,remaining_on_right) VALUES ('$seating_arrangement_id', '$room_id', '$num_students','$left_col','$middle_col','$right_col')";
                $conn->query($insert_detail_sql);
            }

            header("Location: teacher_exam_arrangements_created.php");
            exit();
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Seating Arrangement</title>
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
    </style>
</head>

<body>
    <?php
    include 'teacher_navbar.php';
    ?>

    <div class="container mb-5">
        <h4 class="mt-4 font-weight-bold mb-3">Create Exam Seating Arrangement</h4>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="examArrangementForm">
            <div class="form-group">
                <label for="dep">Select department:</label>
                <select id="dep" class=" form-control" name="department_id" required>
                    <option value="">Select Department</option>
                    <?php
                    if ($department_result->num_rows > 0) {
                        while ($dept_row = $department_result->fetch_assoc()) {
                            echo "<option value='" . $dept_row['id'] . "'>" . $dept_row['department_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="year" for="department_name">Select year:</label>
                <select id="year" class="form-control" name="year" required>
                    <option value="">Select Year</option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exam_name">Exam name:</label>
                <input type="text" class="form-control" id="exam_name" name="exam_name" required>
            </div>

            <div class="form-group">
                <label for="teacher">teacher:</label>

               
                    <?php
                    if ($teachers_result->num_rows > 0) {
                        while ($teacher_row = $teachers_result->fetch_assoc()) {
                    ?>
                            <input type="hidden" value="<?php echo $teacher_row['id']; ?>" class="form-control" id="teacher" name="teacher_id" readonly required>

                            <input type="text" class="form-control" value="<?php echo $teacher_row['username']; ?>" readonly required>
                    <?php
                        }
                    }
                    ?>
          
            </div>

            <div class="form-group">
                <label for="exam_date">Exam Date:</label>
                <input type="date" class="form-control" id="exam_date" name="exam_date" required>
            </div>

            <div class="form-group">
                <label for="start_time">Starting Time:</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
            </div>

            <div class="form-group">
                <label>Select session:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="day_session" name="session[]" value="Day">
                    <label class="form-check-label" for="day_session">Day Session</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="night_session" name="session[]" value="Night">
                    <label class="form-check-label" for="night_session">Night Session</label>
                </div>
            </div>

            <div class="form-group">
                <label for="room">Select exam rooms:</label>
                <select id="room" class="form-control" name="room_id[]" multiple required>
                    <option value="">Select exam room</option>
                    <?php
                    if ($exam_rooms_results->num_rows > 0) {
                        while ($exam_room_row = $exam_rooms_results->fetch_assoc()) {
                            echo "<option value='" . $exam_room_row['room_id'] . "'>" . $exam_room_row['room_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- This div will be populated dynamically based on selected exam rooms -->
            <div id="numStudentsFields"></div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        document.getElementById('room').addEventListener('change', function() {
            var roomSelect = document.getElementById('room');
            var numStudentsFields = document.getElementById('numStudentsFields');
            numStudentsFields.innerHTML = '';

            for (var i = 0; i < roomSelect.selectedOptions.length; i++) {
                var roomId = roomSelect.selectedOptions[i].value;
                var roomName = roomSelect.selectedOptions[i].text;
                var inputField = document.createElement('div');
                inputField.classList.add('form-group');
                inputField.innerHTML = '<label for="num_students_' + roomId + '">Number of students in ' + roomName + ':</label>' +
                    '<input type="number" class="form-control" id="num_students_' + roomId + '" name="num_students' + roomId + '" min="1" required>';
                numStudentsFields.appendChild(inputField);
            }
        });
    </script>
</body>

</html>