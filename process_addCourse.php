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
    $course_id = $conn->real_escape_string($_POST['course_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $teacher_id = (int)$_POST['teacher_id'];

    $check = $conn->query("SELECT id FROM Courses WHERE id='$course_id'");
    if ($check->num_rows > 0) {
        echo "Course ID already exists!";
        exit;
    }

    $sql = "INSERT INTO Courses (id, title, teacher_id) VALUES ('$course_id', '$title', $teacher_id)";
    if ($conn->query($sql)) {
        header("Location: ManageCourses.php?msg=added");
    } else {
        echo "Error: " . $conn->error;
    }
}
