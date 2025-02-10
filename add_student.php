<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $matric_no = $_POST['matric_no'];
    $program = $_POST['program'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO students (name, matric_no, program, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $matric_no, $program, $reason);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - UiTM Dropout Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f4f0, #e8dcd3);
            padding: 20px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h2 {
            text-align: center;
            color: #4e342e;
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            color: #5d4037;
            font-weight: 600;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: #8d6e63;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #6d4c41;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #8d6e63;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
            color: #6d4c41;
        }
        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #4e342e;
        }
        footer a {
            color: #8d6e63;
            text-decoration: none;
            font-weight: 600;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Add New Student</h2>
    <?php if (isset($error)) { echo "<p class=\"error\">$error</p>"; } ?>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="matric_no">Matric No:</label>
        <input type="text" name="matric_no" required>

        <label for="program">Program:</label>
        <input type="text" name="program" required>

        <label for="reason">Reason for Dropout:</label>
        <textarea name="reason" rows="4" required></textarea>

        <input type="submit" value="Add Student">
    </form>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>

    <footer>
        &copy; 2024 <a href="https://uitm.edu.my">Universiti Teknologi MARA</a>. All rights reserved.
    </footer>
</body>
</html>

