<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Check if student ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$student_id = $_GET['id'];

// Delete student from database
$sql = "DELETE FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    header('Location: dashboard.php?message=Student+deleted+successfully');
    exit();
} else {
    echo "Error deleting student: " . $conn->error;
}
?>
