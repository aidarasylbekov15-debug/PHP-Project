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
    header("Location: SignIn.php");
    exit;
}

$email    = trim($_POST['email']);
$password = trim($_POST['password']);

$stmt = $conn->prepare(
    "SELECT id, full_name, password, user_role
     FROM Users
     WHERE email = ? AND status = 'active'"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['login_error'] = "Неверный e-mail или пароль";
    header("Location: SignIn.php");
    exit;
}

$userData = $result->fetch_assoc();

if ($password !== $userData['password']) {
    $_SESSION['login_error'] = "Неверный e-mail или пароль";
    header("Location: SignIn.php");
    exit;
}

$_SESSION['user_id']   = $userData['id'];
$_SESSION['user_name'] = $userData['full_name'];
$_SESSION['role']      = $userData['user_role'];

/* Редирект по роли */
switch ($userData['user_role']) {
    case 'admin':
        header("Location: AdminDash.php");
        break;
    case 'teacher':
        header("Location: TeacherDash.php");
        break;
    case 'student':
        header("Location: StudentDash.php");
        break;
    default:
        header("Location: index.php");
}

exit;
