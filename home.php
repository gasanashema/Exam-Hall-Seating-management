<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Seating Arrangement</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <?php
    include "admin_navbar.php";
    ?>

    <div class="container mt-5">
        <div class="card p-5 shadow-sm border-0">
            <div class="card-body">
                <span class="badge bg-primary text-white mb-3">Administrator Dashboard</span>
                <h1 class="page-title mb-3">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p class="text-muted mb-4 fs-5">You are logged in to the Exam Seating Arrangement control panel. From here, you can manage departments, allocate exam rooms, and view structural arrangements.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="created_arrangements.php" class="btn btn-primary mr-3">View Seating Plans</a>
                    <a href="arrangement.php" class="btn btn-success">Create Arrangement</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>