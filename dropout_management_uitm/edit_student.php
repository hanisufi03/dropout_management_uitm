<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Get student ID from URL
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$student_id = $_GET['id'];

// Fetch student details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: dashboard.php');
    exit();
}

$student = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $matric_no = $_POST['matric_no'];
    $program = $_POST['program'];
    $reason = $_POST['reason'];

    $update_sql = "UPDATE students SET name = ?, matric_no = ?, program = ?, reason = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $name, $matric_no, $program, $reason, $student_id);

    if ($update_stmt->execute()) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Error: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student - UiTM Dropout Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin: 15px 0 5px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Edit Student Information</h2>
    <?php if (isset($error)) { echo "<p class=\"error\">$error</p>"; } ?>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>

        <label for="matric_no">Matric No:</label>
        <input type="text" name="matric_no" value="<?php echo htmlspecialchars($student['matric_no']); ?>" required>

        <label for="program">Program:</label>
        <input type="text" name="program" value="<?php echo htmlspecialchars($student['program']); ?>" required>

        <label for="reason">Reason for Dropout:</label>
        <textarea name="reason" rows="4" required><?php echo htmlspecialchars($student['reason']); ?></textarea>

        <input type="submit" value="Update Student">
    </form>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
