<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db_connection.php";

    $username = $_POST['username'];
    $password = hash('sha512', $_POST['password']); // Hash password using SHA512

    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect to login page after successful registration
        exit();
    } else {
        $error = "Error occurred while registering. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Register Admin</h2>
                    </div>
                    <div class="card-body">
                        <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php" class="btn-link">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>