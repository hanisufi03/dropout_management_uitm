<?php
session_start();
include('config.php');

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: dashboard.php'); // Admin dashboard
    } elseif ($_SESSION['role'] == 'student') {
        header('Location: student_dashboard.php'); // Student dashboard
    }
    exit();
}

// Process login form
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password'])); // Pastikan enkripsi sama dengan DB
    $role = isset($_POST['role']) ? $_POST['role'] : ''; // Periksa jika role dihantar

    if (!empty($role)) {
        // Prepared statement untuk elakkan SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=? AND role=?");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['matric_no'] = $row['matric_no'];

            // Redirect berdasarkan role
            if ($role == 'admin') {
                header('Location: dashboard.php');
            } elseif ($role == 'student') {
                header('Location: student_dashboard.php');
            }
            exit();
        } else {
            $error = "Invalid username, password, or role!";
        }
    } else {
        $error = "Please select a role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UiTM Dropout Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f4f0, #e8dcd3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        h2 {
            font-size: 2.5em;
            color: #4e342e;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #5d4037;
        }
        input[type="text"], input[type="password"], select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus, select:focus {
            border-color: #8d6e63;
            outline: none;
        }
        input[type="submit"] {
            background-color: #8d6e63;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        input[type="submit"]:hover {
            background-color: #6d4c41;
            transform: translateY(-3px);
        }
        .error {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }
        footer {
            margin-top: auto;
            text-align: center;
            font-size: 0.9em;
            color: #4e342e;
            padding: 10px 0;
            width: 100%;
            background: #f1e8e4;
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
    <h2>UiTM Dropout Management Login</h2>

    <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select>

        <input type="submit" value="Login">
    </form>

    <footer>
        &copy; 2024 <a href="https://uitm.edu.my">Universiti Teknologi MARA</a>. All rights reserved.
    </footer>
</body>
</html>


