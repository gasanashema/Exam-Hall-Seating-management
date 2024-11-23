<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['username']) || isset($_SESSION['reg_no'])) {
    header("Location: student-home.php");
    exit();
}

require_once "db_connection.php";

// Fetch departments for dropdown
$department_sql = "SELECT * FROM departments";
$department_result = $conn->query($department_sql);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $reg_no = $_POST["reg_no"];
    $department_id = $_POST["department_id"];
    $year = $_POST["year"];
    $session = $_POST["session"];

    // Check if reg no already exists
    $check_sql = "SELECT * FROM students WHERE reg_no='$reg_no'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $error = "Registration number already exists.";
    } else {
        // Insert student
        $insert_sql = "INSERT INTO students (name, reg_no, department_id, year,session) VALUES ('$name', '$reg_no', '$department_id', '$year','$session')";
        if ($conn->query($insert_sql) === TRUE) {
            $last_id = $conn->insert_id;
            $_SESSION['username'] = $name;
            $_SESSION['reg_no'] = $reg_no; // For student
            $_SESSION['student_id'] = $last_id; // For student
            header("Location: student-home.php");
            exit();
        } else {
            $error = "Error occurred while signing up.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Signup</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #2c3e50;
            color: white;
            border-radius: 20px 20px 0 0;
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #dcdcdc;
        }

        .btn-primary {
            background-color: #2c3e50;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #34495e;
        }

        .alert {
            border-radius: 10px;
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
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Exam Seating Arrangement</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="teacher-login.php">Teacher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="student-login.php">Student</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Student Signup</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="reg_no" placeholder="Registration Number" required>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="department_id" required>
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
                                <select class="form-control" name="year" required>
                                    <option value="">Select Year</option>
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select session:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" required type="radio" id="day_session" name="session" value="Day">
                                    <label class="form-check-label" for="day_session">Day Session</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" required type="radio" id="night_session" name="session" value="Night">
                                    <label class="form-check-label" for="night_session">Night Session</label>
                                </div>
                            </div>
                            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                            <button type="submit" class="btn btn-primary btn-block">Signup</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Already have an account? <a href="student-login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>