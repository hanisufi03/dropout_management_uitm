<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$matric_no = $_SESSION['username'];

// Check if student has already submitted data
$check_query = "SELECT * FROM students WHERE matric_no = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("s", $matric_no);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $form_filled = true;
} else {
    $form_filled = false;
}

// Handle form submission (new data)
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $program = $_POST['program'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO students (name, matric_no, program, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $matric_no, $program, $reason);

    if ($stmt->execute()) {
        header('Location: student_dashboard.php');
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Handle form update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = $_POST['name'];
    $program = $_POST['program'];
    $reason = $_POST['reason'];

    $update_query = "UPDATE students SET name=?, program=?, reason=? WHERE matric_no=?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssss", $name, $program, $reason, $matric_no);

    if ($update_stmt->execute()) {
        header('Location: student_dashboard.php');
        exit();
    } else {
        $error = "Error updating record: " . $update_stmt->error;
    }
}

// Check if edit mode is requested
$edit_mode = isset($_GET['edit']) && $_GET['edit'] == 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UiTM Dropout Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f8f4f0, #e8dcd3); padding: 20px; margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; }
        h2 { text-align: center; color: #4e342e; }
        form { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 500px; }
        label { display: block; margin: 15px 0 5px; color: #5d4037; font-weight: 600; }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 10px; font-size: 14px; }
        input[type="submit"], .edit-btn { background-color: #8d6e63; color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background 0.3s ease; margin-top: 10px; }
        input[type="submit"]:hover, .edit-btn:hover { background-color: #6d4c41; }
        .error { color: red; text-align: center; margin-bottom: 20px; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #8d6e63; font-weight: 600; }
        .back-link:hover { text-decoration: underline; color: #6d4c41; }
        footer { margin-top: 20px; text-align: center; font-size: 14px; color: #4e342e; }
        footer a { color: #8d6e63; text-decoration: none; font-weight: 600; }
        footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

    <?php if ($form_filled && !$edit_mode): ?>
        <p>Your information:</p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Matric No:</strong> <?php echo htmlspecialchars($student['matric_no']); ?></p>
        <p><strong>Program:</strong> <?php echo htmlspecialchars($student['program']); ?></p>
        <p><strong>Reason for Dropout:</strong> <?php echo htmlspecialchars($student['reason']); ?></p>

        <!-- Butang Edit -->
        <a href="?edit=true" class="edit-btn">Edit Information</a>

    <?php else: ?>
        <?php if (!empty($error)) { echo "<p class=\"error\">$error</p>"; } ?>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $form_filled ? htmlspecialchars($student['name']) : ''; ?>" required>

            <label for="program">Program:</label>
            <input type="text" name="program" value="<?php echo $form_filled ? htmlspecialchars($student['program']) : ''; ?>" required>

            <label for="reason">Reason for Dropout:</label>
            <textarea name="reason" rows="4" required><?php echo $form_filled ? htmlspecialchars($student['reason']) : ''; ?></textarea>

            <?php if ($form_filled): ?>
                <input type="submit" name="update" value="Update Information">
            <?php else: ?>
                <input type="submit" name="submit" value="Submit Information">
            <?php endif; ?>
        </form>
    <?php endif; ?>

    <a href="logout.php" class="back-link">Logout</a>

    <footer>
        &copy; 2024 <a href="https://uitm.edu.my">Universiti Teknologi MARA</a>. All rights reserved.
    </footer>
</body>
</html>
