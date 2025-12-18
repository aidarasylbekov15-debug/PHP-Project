<?php
session_start();

$host = "localhost";
$db   = "CMS";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Register.php");
    exit;
}

$full_name = trim($_POST['full_name']);
$mobile    = trim($_POST['mobile']);
$email     = trim($_POST['email']);
$address   = trim($_POST['address']);
$city      = trim($_POST['city']);
$password  = trim($_POST['password']);
$gender    = trim($_POST['gender']);

$check = $conn->prepare("SELECT id FROM Users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $_SESSION['register_error'] = "Пользователь с таким e-mail уже существует";
    header("Location: Register.php");
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO Users
     (full_name, mobile, email, address, city, password, gender, user_role, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, 'no_role', 'active')"
);

$stmt->bind_param(
    "sssssss",
    $full_name,
    $mobile,
    $email,
    $address,
    $city,
    $password,
    $gender
);

if ($stmt->execute()) {
    header("Location: SignIn.php");
    exit;
} else {
    $_SESSION['register_error'] = "Ошибка регистрации";
    header("Location: Register.php");
    exit;
}
