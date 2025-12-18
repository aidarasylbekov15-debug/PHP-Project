<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: SignIn.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enrollment_id'])) {
    $enrollment_id = (int) $_POST['enrollment_id'];

    $sql = "DELETE FROM Enrollments WHERE id = $enrollment_id";
    if ($conn->query($sql)) {
        header("Location: StudentEnrollment.php?msg=deleted");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
