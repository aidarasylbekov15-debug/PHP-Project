<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: SignIn.php");
    exit;
}

if (!isset($_GET['group_id'])) {
    header("Location: StudentDash.php");
    exit;
}

$studentId = $_SESSION['user_id'];
$groupId = $_GET['group_id'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM Enrollments WHERE student_id = ? AND group_id = ?");
$stmt->bind_param("is", $studentId, $groupId);
$stmt->execute();
$stmt->close();

header("Location: StudentDash.php");
exit;
