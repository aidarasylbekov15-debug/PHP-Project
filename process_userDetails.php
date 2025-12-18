<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header("Location: SignIn.php");
    exit;
}


$pdo = new PDO(
    "mysql:host=localhost;dbname=cms;charset=utf8",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

function getUserById($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'update_profile') {

    $id = $_SESSION['user_id'];

    $full_name = trim($_POST['full_name']);
    $mobile    = trim($_POST['mobile']);
    $city      = trim($_POST['city']);
    $address   = trim($_POST['address']);
    $gender    = $_POST['gender'] ?: null;
    $password  = $_POST['password'];

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET 
                full_name=?, mobile=?, city=?, address=?, gender=?, password=?
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $full_name, $mobile, $city, $address, $gender, $password, $id
        ]);
    } else {
        $sql = "UPDATE users SET 
                full_name=?, mobile=?, city=?, address=?, gender=?
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $full_name, $mobile, $city, $address, $gender, $id
        ]);
    }

    $_SESSION['user_name'] = $full_name;

    switch ($_SESSION['role']) {
        case 'admin':
            $redirect = 'ProfileAdmin.php';
            break;

        case 'teacher':
            $redirect = 'ProfileTeacher.php';
            break;

        case 'student':
            $redirect = 'ProfileStudent.php';
            break;

        default:
            $redirect = 'SignIn.php';
    }

    header("Location: $redirect?success=1");
    exit;
}
