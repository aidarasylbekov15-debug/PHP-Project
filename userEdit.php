<?php
session_start();

/* Защита страницы */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];
$conn = new mysqli("localhost", "root", "", "CMS");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверяем, что передан ID пользователя
if (!isset($_GET['id'])) {
    header("Location: ManageUser.php");
    exit;
}

$id = (int)$_GET['id'];

// Получаем данные пользователя
$result = $conn->query("SELECT * FROM users WHERE id=$id");
if ($result->num_rows === 0) {
    echo "User not found!";
    exit;
}
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>User Manage</title>

    <!-- Bootstrap -->
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

<!-- HEADER -->
<nav class="navbar navbar-dark bg-dark shadow-sm px-4" style="padding-top: 25px; padding-bottom: 25px;">
    <span class="navbar-brand text-white"><img src="system.png" class="icon2" style="width: 35px; height: 35px;" />Courses Management System</span>
    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle w-100" style="min-width: 550px;" data-bs-toggle="dropdown">
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

<!-- CONTENT -->
<div class="container-fluid flex-grow-1">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-3 sidebar p-3">
            <a class="sidebar-btn" href="AdminDash.php">Dashboard</a>
            <a class="sidebar-btn" href="ProfileAdmin.php">Profile</a>
            <a class="sidebar-btn active" href="ManageUser.php">User Manage</a>
            <a class="sidebar-btn" href="ManageCourse.php">Course Manage</a>
            <a class="sidebar-btn" href="ManageGroup.php">Group Manage</a>
            <a class="sidebar-btn" href="StudentEnrollment.php">Student Enrollment</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <!-- MAIN -->
        <main class="col-9 p-4">
            <h1 class="mb-4">Edit User</h1>
            <label class="mb-4">Admin . Edit User</label>

            <form action="process_userEdit.php" method="post">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($user['mobile']) ?>">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
                </div>

                <div class="mb-3">
                    <label>City</label>
                    <select name="city" class="form-control" required>
                        <?php
                        $cities = ['Bishkek', 'Osh', 'Toktogul'];
                        foreach ($cities as $city) {
                            $selected = ($user['city'] === $city) ? 'selected' : '';
                            echo "<option value='$city' $selected>$city</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="user_role" class="form-control">
                        <?php
                        $roles = ['admin', 'teacher', 'student', 'no_role'];
                        foreach ($roles as $role) {
                            $selected = ($user['user_role'] === $role) ? 'selected' : '';
                            echo "<option value='$role' $selected>$role</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <?php
                        $genders = ['male', 'female'];
                        foreach ($genders as $gender) {
                            $selected = ($user['gender'] === $gender) ? 'selected' : '';
                            echo "<option value='$gender' $selected>$gender</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <?php
                        $statuses = ['active', 'disabled'];
                        foreach ($statuses as $status) {
                            $selected = ($user['status'] === $status) ? 'selected' : '';
                            echo "<option value='$status' $selected>$status</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="ManageUser.php" class="btn btn-secondary">Cancel</a>
            </form>

        </main>
    </div>
</div>

<!-- FOOTER -->
<footer class="text-center text-black-50 py-2">
    PHP_2025
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
