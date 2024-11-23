<?php
session_start();

// Redirect if user is already logged in
if (isset($_SESSION['username']) || isset($_SESSION['reg_no'])) {
    header("Location: student-home.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once "db_connection.php";

    // Retrieve form data
    $reg_no = $_POST['reg_no'];
    $password = hash('sha512', $_POST['password']); // Hash password using SHA512

    // Prepare SQL statement
    $sql = "SELECT * FROM students WHERE reg_no = '$reg_no'";
    $result = mysqli_query($conn, $sql);

    // Check if the query executed successfully
    if ($result === false) {
        die('Error in executing SQL query: ' . mysqli_error($conn));
    }

    // Check if the user exists and password matches "1234" manually
    if (mysqli_num_rows($result) == 1 && $password === hash('sha512', '1234')) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['reg_no'] = $row['reg_no']; // Set session variable for registration number
        $_SESSION['username'] = $row['name']; // Set session variable for username
        $_SESSION['student_id'] = $row['id']; // Set session variable for username
        header("Location: student-home.php"); // Redirect to home page
        exit();
    } else {
        $error = "Invalid registration number or password";
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
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
            <a class="navbar-brand text-white" href="#">Exam Seating Arrangement</a>
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

    <div class="container" style="margin-top: 150px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Student Login</h2>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" class="form-control mb-3" name="reg_no" placeholder="Registration Number" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
                            </div>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block py-2">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="student-signup.php">Sign up here</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>