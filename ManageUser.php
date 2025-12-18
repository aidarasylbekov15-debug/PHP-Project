<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];
$conn = new mysqli("localhost", "root", "", "CMS");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['toggle_id'])) {
    $id = (int)$_GET['toggle_id'];
    $currentStatus = $conn->query("SELECT status FROM users WHERE id=$id")->fetch_row()[0];
    $newStatus = ($currentStatus === 'active') ? 'disabled' : 'active';
    $conn->query("UPDATE users SET status='$newStatus' WHERE id=$id");
    header("Location: ManageUser.php");
    exit;
}

$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

$sql = "SELECT * FROM users";
if (!empty($search)) {
    $sql .= " WHERE full_name LIKE '%$search%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>User Manage</title>


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

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-disabled {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">


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

<div class="container-fluid flex-grow-1">
    <div class="row">

        <aside class="col-3 sidebar p-3">
            <a class="sidebar-btn" href="AdminDash.php">Dashboard</a>
            <a class="sidebar-btn" href="ProfileAdmin.php">Profile</a>
            <a class="sidebar-btn active" href="ManageUser.php">User Manage</a>
            <a class="sidebar-btn" href="ManageCourse.php">Course Manage</a>
            <a class="sidebar-btn" href="ManageGroup.php">Group Manage</a>
            <a class="sidebar-btn" href="StudentEnrollment.php">Student Enrollment</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">
            <h1 class="mb-4">User Manage</h1>
            <label class="mb-4">Admin . User Manage</label>

            <form class="mb-4 d-flex" method="get" action="ManageUser.php">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by full name" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary me-2">Search</button>
                <a href="ManageUser.php" class="btn btn-secondary">Reset</a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Role</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['mobile']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['address']) ?></td>
                            <td><?= htmlspecialchars($user['city']) ?></td>
                            <td><?= htmlspecialchars($user['user_role']) ?></td>
                            <td><?= htmlspecialchars($user['gender']) ?></td>
                            <td class="<?= $user['status'] === 'active' ? 'status-active' : 'status-disabled' ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td>
                                <a href="userEdit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary mb-1">Edit</a>
                                <a href="ManageUser.php?toggle_id=<?= $user['id'] ?>" 
                                   class="btn btn-sm <?= $user['status'] === 'active' ? 'btn-danger' : 'btn-success' ?> mb-1"
                                   onclick="return confirm('Are you sure you want to <?= $user['status'] === 'active' ? 'disable' : 'activate' ?> this user?');">
                                    <?= $user['status'] === 'active' ? 'Disable' : 'Activate' ?>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>


<footer class="text-center text-black-50 py-2">
    PHP_2025
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
