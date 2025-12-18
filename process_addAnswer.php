<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: SignIn.php");
    exit;
}

$studentId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lesson_id']) && isset($_FILES['task_file'])) {
    $lessonId = intval($_POST['lesson_id']);
    $file = $_FILES['task_file'];

    $lessonCheck = $conn->prepare("SELECT id FROM Lessons WHERE id = ?");
    $lessonCheck->bind_param("i", $lessonId);
    $lessonCheck->execute();
    $lessonCheckResult = $lessonCheck->get_result();

    if ($lessonCheckResult->num_rows === 0) {
        die("Error: Lesson not found.");
    }

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/assignments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = time() . "_" . basename($file['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $conn->prepare("
                INSERT INTO Assignments (lesson_id, student_id, task)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iis", $lessonId, $studentId, $targetPath);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header("Location: StudentDash.php");
exit;
