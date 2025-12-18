<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>

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
            <li><a class="dropdown-item" href="ProfileStudent.php">Profile</a></li>
            <li><a class="dropdown-item" href="StudentDash.php">Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="SignOut.php">Sign Out</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid flex-grow-1">
    <div class="row">

        <aside class="col-3 sidebar p-3">


            <a class="sidebar-btn" href="StudentDash.php">Dashboard</a>
            <a class="sidebar-btn active" href="ProfileStudent.php">Profile</a>
            <a class="sidebar-btn" href="LessonsStudent.php">Lessons</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">

            <h1 class="mb-4">Profile</h1>
            <lable class="mb-4">Student . Profile</lable>
            
            <?php
                require_once "process_userDetails.php";
                $user = getUserById($_SESSION['user_id']);
            ?>

            <form method="POST" action="process_userDetails.php" class="row g-3 mt-3">

                <input type="hidden" name="action" value="update_profile">

                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control"
                        value="<?= htmlspecialchars($user['full_name']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control"
                        value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control"
                        value="<?= ucfirst(htmlspecialchars($user['user_role'])) ?>"
                        disabled>
                 </div>


                <div class="col-md-6">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control"
                        value="<?= htmlspecialchars($user['mobile']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <select name="city" class="form-select">
                        <option value="">Select</option>
                        <option value="Bishkek" <?= $user['city'] == 'Bishkek' ? 'selected' : '' ?>>Bishkek</option>
                        <option value="Osh" <?= $user['city'] == 'Osh' ? 'selected' : '' ?>>Bishkek</option>
                        <option value="Toktogul" <?= $user['city'] == 'Toktogul' ? 'selected' : '' ?>>Toktogul</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                        value="<?= htmlspecialchars($user['address']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Leave empty to keep current">
                </div>

                <div class="col-12">
                    <button class="btn btn-primary">
                        Update Profile
                    </button>
                </div>

            </form>

            
            
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
