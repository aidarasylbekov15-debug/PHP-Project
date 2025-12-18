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
    $group_id = $conn->real_escape_string($_POST['group_id']);
    $group_name = $conn->real_escape_string($_POST['group_name']);
    $course_id = $conn->real_escape_string($_POST['course_id']);

    $check = $conn->query("SELECT id FROM Groups WHERE id='$group_id'");
    if ($check->num_rows > 0) {
        echo "Group ID already exists!";
        exit;
    }

    $sql = "INSERT INTO Groups (id, name, course_id) VALUES ('$group_id', '$group_name', '$course_id')";
    if ($conn->query($sql)) {
        header("Location: ManageGroup.php?msg=added");
    } else {
        echo "Error: " . $conn->error;
    }
}
