<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];

$conn = new mysqli("localhost", "root", "", "CMS");

$teacherCount = $conn->query(
    "SELECT COUNT(*) FROM users
     WHERE user_role='Teacher' AND status='active'"
)->fetch_row()[0];

$studentCount = $conn->query(
    "SELECT COUNT(*) FROM users
     WHERE user_role='Student' AND status='active'"
)->fetch_row()[0];

$courseCount = $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0];
$groupCount  = $conn->query("SELECT COUNT(*) FROM groups")->fetch_row()[0];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .sidebar {
            background-color: #0D6EFD1A;
            min-height: 100%;
            font-size: 1.2rem;
        }

        .sidebar-btn {
            display: flex;
            justify-content: center;
            align-items: center;

            padding: 12px 14px; 
            margin-bottom: 10px;

            text-decoration: none;
            color: #000;
            border-radius: 6px;
            border: 1px solid #000;

            font-size: 1rem;
            font-weight: 400;

            text-align: center;
        }

        .sidebar-btn:hover,
        .sidebar-btn.active {
            background-color: #000;
            color: #fff;
        }

        .stat-box {
            background: #0D6EFD40;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            font-size: 1.5rem;
            font-weight: 400;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">


<nav class="navbar navbar-dark bg-dark shadow-sm px-4" style="padding-top: 25px; padding-bottom: 25px;">

    <span class="navbar-brand text-white"><img src="system.png" class="icon2" style="width: 35px; height: 35px;" />Courses Management System</span>

    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle w-100"
                style="min-width: 550px;"
                data-bs-toggle="dropdown">
            <?= htmlspecialchars($fullName) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="ProfileAdmin.php">Profile</a></li>
            <li><a class="dropdown-item" href="AdminDash.php">Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="SignOut.php">Sign Out</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid flex-grow-1">
    <div class="row">

        <aside class="col-3 sidebar p-3">
            <a class="sidebar-btn active" href="AdminDash.php">Dashboard</a>
            <a class="sidebar-btn" href="ProfileAdmin.php">Profile</a>
            <a class="sidebar-btn" href="ManageUser.php">User Manage</a>
            <a class="sidebar-btn" href="ManageCourse.php">Course Manage</a>
            <a class="sidebar-btn" href="ManageGroup.php">Group Manage</a>
            <a class="sidebar-btn" href="StudentEnrollment.php">Student Enrollment</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">

            <h1 class="mb-4">Dashboard</h1>
            <label class="mb-4">Admin . Dashboard</label>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="stat-box">
                        <div>Total Teacher Count</div>
                        <h4><?= $teacherCount ?></h4>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stat-box">
                        <div>Total Student Count</div>
                        <h4><?= $studentCount ?></h4>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stat-box">
                        <div>Total Course Count</div>
                        <h4><?= $courseCount ?></h4>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stat-box">
                        <div>Total Group Count</div>
                        <h4><?= $groupCount ?></h4>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>


<footer class="text-center text-black-50 py-2">
    PHP_2025
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="assets/js/activity_chart.js"></script>
</body>
</html>
