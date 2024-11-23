<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once "db_connection.php";

    // Retrieve form data
    $username = $_POST['username'];
    $password =$_POST['password']; // Hash password using SHA512
    // $password = hash('sha512', $_POST['password']); // Hash password using SHA512

    // Prepare SQL statement
    $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $_SESSION['username'] = $username; // Set session variable
        header("Location: home.php"); // Redirect to home page
    } else {
        $error = "Invalid username or password";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
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

        .container {
            /* margin-top: 100px; */
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

        .btn-link {
            color: #2c3e50;
            font-weight: bold;
        }

        .btn-link:hover {
            text-decoration: none;
            color: #34495e;
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
                        <a class="nav-link text-primary" href="login.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="teacher-login.php">Teacher</a>
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
                        <h2>Admin Login</h2>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" class="form-control mb-3" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
                            </div>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block py-2">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>