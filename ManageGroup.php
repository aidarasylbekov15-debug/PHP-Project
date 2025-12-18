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

$coursesResult = $conn->query("SELECT id, title FROM Courses ORDER BY title");

$groupsResult = $conn->query("
    SELECT g.id, g.name, c.title AS course_title
    FROM Groups g
    LEFT JOIN Courses c ON g.course_id = c.id
    ORDER BY g.id
");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Group Manage</title>


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
            <a class="sidebar-btn" href="ManageUser.php">User Manage</a>
            <a class="sidebar-btn" href="ManageCourse.php">Course Manage</a>
            <a class="sidebar-btn active" href="ManageGroup.php">Group Manage</a>
            <a class="sidebar-btn" href="StudentEnrollment.php">Student Enrollment</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">

            <h1 class="mb-4">Group Manage</h1>
            <label class="mb-4">Admin . Group Manage</label>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Add New Group</h5>
                    <form action="process_addGroup.php" method="post">
                        <div class="mb-3">
                            <label>Group ID</label>
                            <input type="text" name="group_id" class="form-control" maxlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label>Group Name</label>
                            <input type="text" name="group_name" class="form-control" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label>Assign Course</label>
                            <select name="course_id" class="form-control" required>
                                <option value="">-- Select Course --</option>
                                <?php while ($course = $coursesResult->fetch_assoc()): ?>
                                    <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Add Group</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">All Groups</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Group ID</th>
                                <th>Group Name</th>
                                <th>Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($group = $groupsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($group['id']) ?></td>
                                    <td><?= htmlspecialchars($group['name']) ?></td>
                                    <td><?= htmlspecialchars($group['course_title'] ?? 'Not assigned') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
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
