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

if (!isset($_POST['id'])) {
    header("Location: ManageUser.php");
    exit;
}

$id = (int)$_POST['id'];
$full_name = $conn->real_escape_string($_POST['full_name']);
$mobile = $conn->real_escape_string($_POST['mobile']);
$email = $conn->real_escape_string($_POST['email']);
$address = $conn->real_escape_string($_POST['address']);
$city = $conn->real_escape_string($_POST['city']);
$user_role = $conn->real_escape_string($_POST['user_role']);
$gender = $conn->real_escape_string($_POST['gender']);
$status = $conn->real_escape_string($_POST['status']);

$sql = "UPDATE users SET 
    full_name='$full_name',
    mobile='$mobile',
    email='$email',
    address='$address',
    city='$city',
    user_role='$user_role',
    gender='$gender',
    status='$status'
    WHERE id=$id";

if ($conn->query($sql)) {
    header("Location: ManageUser.php?msg=updated");
} else {
    echo "Error: " . $conn->error;
}
