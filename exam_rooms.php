<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// MySQL connection
require_once "db_connection.php";

// Fetch exam rooms
$sql = "SELECT * FROM exam_room";
$result = $conn->query($sql);

// Check if exam room form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_room"])) {
        $room_name = $_POST["room_name"];

        // Insert exam room
        $insert_sql = "INSERT INTO exam_room (room_name) VALUES ('$room_name')";
        if ($conn->query($insert_sql) === TRUE) {
            header("Location: exam_rooms.php");
            exit();
        } else {
            $error = "Error occurred while adding exam room.";
        }
    } elseif (isset($_POST["edit_room"])) {
        $room_id = $_POST["room_id"];
        $room_name = $_POST["edit_room_name"];

        // Update exam room
        $update_sql = "UPDATE exam_room SET room_name = '$room_name' WHERE room_id = $room_id";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: exam_rooms.php");
            exit();
        } else {
            $error = "Error occurred while updating exam room.";
        }
    } elseif (isset($_POST["delete_room"])) {
        $room_id = $_POST["delete_room_id"];

        // Delete exam room
        $delete_sql = "DELETE FROM exam_room WHERE room_id = $room_id";
        if ($conn->query($delete_sql) === TRUE) {
            header("Location: exam_rooms.php");
            exit();
        } else {
            $error = "Error occurred while deleting exam room.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Rooms</title>
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

        .modal-dialog {
            max-width: 500px;
        }
    </style>
</head>

<body>
    <?php
    include "admin_navbar.php";
    ?>

    <div class="container mt-4">
        <h2>Exam Rooms</h2>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoomModal">Add Room</button>

        <!-- Display exam rooms in a table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['room_name'] . "</td>";
                        echo "<td>
                                <button type='button' class='btn btn-sm btn-primary' data-toggle='modal' data-target='#editRoomModal" . $row['room_id'] . "'>Edit</button>
                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#deleteRoomModal" . $row['room_id'] . "'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No exam rooms found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for adding room -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!-- Modal content -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Add Room</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="room_name">Room Name:</label>
                            <input type="text" class="form-control" id="room_name" name="room_name" required>
                        </div>
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_room">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals for editing and deleting room -->
    <?php
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
            <!-- Edit Room Modal -->
            <div class="modal fade" id="editRoomModal<?php echo $row['room_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!-- Modal content -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
                                <div class="form-group">
                                    <label for="edit_room_name">Room Name:</label>
                                    <input type="text" class="form-control" id="edit_room_name" name="edit_room_name" value="<?php echo $row['room_name']; ?>" required>
                                </div>
                                <?php if (isset($error)) { ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="edit_room">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Room Modal -->
            <div class="modal fade" id="deleteRoomModal<?php echo $row['room_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteRoomModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!-- Modal content -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteRoomModalLabel">Delete Room</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="delete_room_id" value="<?php echo $row['room_id']; ?>">
                                <p>Are you sure you want to delete room '<?php echo $row['room_name']; ?>'?</p>
                                <?php if (isset($error)) { ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger" name="delete_room">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    <?php
        }
    }
    ?>

    <!-- JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>