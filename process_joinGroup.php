<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: SignIn.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['group_id']) || !isset($_POST['course_id'])) {
    header("Location: StudentDash.php");
    exit;
}

$studentId = $_SESSION['user_id'];
$groupId = $_POST['group_id'];
$courseId = $_POST['course_id'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$checkStmt = $conn->prepare("SELECT id FROM Enrollments WHERE student_id = ? AND group_id = ?");
$checkStmt->bind_param("is", $studentId, $groupId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $stmt = $conn->prepare("
        INSERT INTO Enrollments (student_id, course_id, group_id)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iss", $studentId, $courseId, $groupId);
    $stmt->execute();
    $stmt->close();
}

$checkStmt->close();

header("Location: StudentDash.php");
exit;
