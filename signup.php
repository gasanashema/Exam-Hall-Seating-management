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
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="auth-body">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card auth-card">
                    <div class="card-header text-center">
                        <h2>Register Admin</h2>
                    </div>
                    <div class="card-body">
                        <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" class="form-control mb-4" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mb-4" name="password" placeholder="Password" required>
                            </div>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger mb-4"><?php echo $error; ?></div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block w-100 mb-3">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php" class="text-info font-weight-bold">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>