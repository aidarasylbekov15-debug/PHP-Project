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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $group_id = $_POST['group_id'];

    if (!$student_id || !$course_id || !$group_id) {
        die("All fields are required.");
    }

    $check = $conn->prepare("SELECT id FROM Enrollments WHERE student_id=? AND course_id=? AND group_id=?");
    $check->bind_param("iss", $student_id, $course_id, $group_id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        die("This enrollment already exists.");
    }

    $stmt = $conn->prepare("INSERT INTO Enrollments (student_id, course_id, group_id) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $student_id, $course_id, $group_id);

    if ($stmt->execute()) {
        header("Location: StudentEnrollment.php?msg=added");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
