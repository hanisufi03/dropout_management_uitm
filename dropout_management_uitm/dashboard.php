<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Fetch students from database
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM Dropout Management - Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f4f0, #e8dcd3);
            padding: 20px;
            margin: 0;
        }
        h2 {
            text-align: center;
            color: #4e342e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #a1887f;
            color: white;
        }
        tr:hover {
            background-color: #f0eae6;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #6d4c41;
            font-weight: 600;
        }
        .actions a:hover {
            text-decoration: underline;
            color: #5d4037;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .top-bar h2 {
            margin: 0;
            font-size: 22px;
            color: #4e342e;
        }
        .top-bar a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #8d6e63;
            color: white;
            border-radius: 8px;
            margin-left: 10px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .top-bar a:hover {
            background-color: #6d4c41;
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
    <div class="top-bar">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <div>
            <a href="add_student.php">Add Student</a>
            <a href="export_pdf.php">Export PDF</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Matric No</th>
            <th>Program</th>
            <th>Reason for Dropout</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['matric_no']; ?></td>
                    <td><?php echo $row['program']; ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td class="actions">
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="6" style="text-align: center;">No students found.</td>
            </tr>
        <?php } ?>
    </table>

    <footer>
        &copy; 2024 <a href="https://uitm.edu.my">Universiti Teknologi MARA</a>. All rights reserved.
    </footer>
</body>
</html>
