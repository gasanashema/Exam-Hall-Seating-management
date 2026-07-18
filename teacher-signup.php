<?php
session_start();

if (isset($_SESSION['username']) || isset($_SESSION['email'])) {
    header("Location: teachers_home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db_connection.php";

    $username = $_POST['username'];
    $email = $_POST['email'];

    $check_sql = "SELECT * FROM teachers WHERE email='$email'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        $insert_sql = "INSERT INTO teachers (username, email) VALUES ('$username', '$email')";
        if ($conn->query($insert_sql) === TRUE) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $last_id = $conn->insert_id;
            $_SESSION['teacher_id'] = $last_id;
            header("Location: teachers_home.php");
            exit();
        } else {
            $error = "Error occurred while signing up.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Signup</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="auth-body">
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
                        <a class="nav-link active" href="teacher-login.php">Teacher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student-login.php">Student</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card auth-card">
                    <div class="card-header text-center">
                        <h2>Teacher Signup</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group mb-4">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group mb-4">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger mb-4"><?php echo $error; ?></div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block w-100 mb-3">Signup</button>
                        </form>
                        <div class="text-center mt-3">
                            <p class="text-white-50 mb-0">Already have an account? <a href="teacher-login.php" class="text-info font-weight-bold">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>