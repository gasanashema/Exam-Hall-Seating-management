<?php
session_start();

if (isset($_SESSION['username']) || isset($_SESSION['email'])) {
    header("Location: teachers_home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db_connection.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    $check_sql = "SELECT * FROM teachers WHERE email='$email'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $teacher = $check_result->fetch_assoc();
        if ($password == '1234567') {
            $_SESSION['email'] = $teacher['email'];
            $_SESSION['username'] = $teacher['username'];
            $_SESSION['teacher_id'] = $teacher['id'];
            header("Location: teachers_home.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
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
                        <a class="nav-link text-primary" href="teacher-login.php">Teacher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student-login.php">Student</a>
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
                        <h2>Teacher Login</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="email" class="form-control mb-3" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
                            </div>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger mb-2"><?php echo $error; ?></div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            Don't have an account? <a href="teacher-signup.php">Signup here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>