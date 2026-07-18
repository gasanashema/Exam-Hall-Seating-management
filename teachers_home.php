<?php
session_start();

// Redirect if teacher is not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: teacher-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Home</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <?php
    include 'teacher_navbar.php';
    ?>
    <div class="container mt-5">
        <div class="card p-5 shadow-sm border-0">
            <div class="card-body">
                <span class="badge bg-success text-white mb-3">Teacher Portal</span>
                <h1 class="page-title mb-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p class="text-muted mb-4 fs-5">Here, you can review current exam seating arrangements, assign students, and check seating plans.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="teacher_exam_arrangements_created.php" class="btn btn-primary mr-3">View Seating Plans</a>
                    <a href="teacher_exam_arrangement.php" class="btn btn-success">Create Seating Plan</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#num_rooms").on("change", function() {
                var numRooms = $(this).val();
                var roomDetails = "";
                for (var i = 1; i <= numRooms; i++) {
                    roomDetails += '<div class="card mt-3">';
                    roomDetails += '<div class="card-header">Room ' + i + ' Details</div>';
                    roomDetails += '<div class="card-body">';
                    roomDetails += '<div class="form-group">';
                    roomDetails += '<label for="room_no_' + i + '">Room Number:</label>';
                    roomDetails += '<input type="text" class="form-control" name="room_no_' + i + '" required>';
                    roomDetails += '</div>';
                    roomDetails += '<div class="form-group">';
                    roomDetails += '<label for="num_desks_' + i + '">Number of Desks:</label>';
                    roomDetails += '<input type="number" class="form-control" name="num_desks_' + i + '" required>';
                    roomDetails += '</div>';
                    roomDetails += '<div class="form-group">';
                    roomDetails += '<label for="num_students_' + i + '">Number of Students:</label>';
                    roomDetails += '<input type="number" class="form-control" name="num_students_' + i + '" required>';
                    roomDetails += '</div>';
                    roomDetails += '<div class="form-group">';
                    roomDetails += '<label for="num_rows_' + i + '">Number of Rows:</label>';
                    roomDetails += '<input type="number" class="form-control" name="num_rows_' + i + '" required>';
                    roomDetails += '</div>';
                    roomDetails += '<div class="form-group">';
                    roomDetails += '<label for="num_columns_' + i + '">Number of Columns:</label>';
                    roomDetails += '<input type="number" class="form-control" name="num_columns_' + i + '" required>';
                    roomDetails += '</div>';
                    roomDetails += '</div>';
                    roomDetails += '</div>';
                }
                $("#room_details").html(roomDetails);
            });
        });
    </script>
</body>

</html>