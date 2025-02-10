<?php
session_start();
include('config.php');




// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if (!file_exists('fpdf.php')) {
    die('FPDF file not found!');
}

// Panggil terus fpdf.php sebab dah pindah ke folder utama
require('fpdf.php');

// Fetch student data
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'UiTM Dropout Management - Student List', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(40, 10, 'Matric No', 1);
$pdf->Cell(50, 10, 'Program', 1);
$pdf->Cell(60, 10, 'Reason for Dropout', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['name'], 1);
        $pdf->Cell(40, 10, $row['matric_no'], 1);
        $pdf->Cell(50, 10, $row['program'], 1);
        $pdf->Cell(60, 10, $row['reason'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No student data available.', 1, 1, 'C');
}

$pdf->Output('D', 'student_list.pdf');
exit();
?>
