<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$teacherId = $_SESSION['user_id'];

$coursesSql = "
    SELECT c.id AS course_id, c.title AS course_title
    FROM Courses c
    WHERE c.teacher_id = ?
";
$coursesStmt = $conn->prepare($coursesSql);
$coursesStmt->bind_param("i", $teacherId);
$coursesStmt->execute();
$coursesResult = $coursesStmt->get_result();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>

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

<nav class="navbar navbar-dark bg-dark shadow-sm px-4" style="padding-top: 25px; padding-bottom: 25px;">

    <span class="navbar-brand text-white"><img src="system.png" class="icon2" style="width: 35px; height: 35px;" />Courses Management System</span>

    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle w-100"
            style="min-width: 550px;"
                data-bs-toggle="dropdown">
            <?= htmlspecialchars($fullName) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="ProfileTeacher.php">Profile</a></li>
            <li><a class="dropdown-item" href="TeacherDash.php">Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="SignOut.php">Sign Out</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid flex-grow-1">
    <div class="row">

        <aside class="col-3 sidebar p-3">


            <a class="sidebar-btn active" href="TeacherDash.php">Dashboard</a>
            <a class="sidebar-btn" href="ProfileTeacher.php">Profile</a>
            <a class="sidebar-btn" href="NoteAdd.php">Lesson Notes</a>
            <a class="sidebar-btn" href="AssignmentGrade.php">Assignments</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">

            <h1 class="mb-4">Dashboard</h1>



            <div class="row g-4">
                <?php if ($coursesResult->num_rows === 0): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            You are not assigned to any courses yet.
                        </div>
                    </div>
                <?php else: ?>

                    <?php while ($course = $coursesResult->fetch_assoc()): ?>

                        <?php
                        // Получаем группы для курса
                        $groupsSql = "
                            SELECT id, name
                            FROM Groups
                            WHERE course_id = ?
                        ";
                        $groupsStmt = $conn->prepare($groupsSql);
                        $groupsStmt->bind_param("s", $course['course_id']);
                        $groupsStmt->execute();
                        $groupsResult = $groupsStmt->get_result();
                        ?>

                        <?php while ($group = $groupsResult->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">
                                            <?= htmlspecialchars($course['course_title']) ?>
                                        </h5>

                                        <p class="card-text">
                                            <strong>Group ID:</strong>
                                            <?= htmlspecialchars($group['id']) ?>
                                        </p>

                                        <div class="mt-auto">
                                            <a href="NoteAdd.php?group_id=<?= $group['id'] ?>"
                                                class="btn btn-primary w-100">
                                                Lesson Notes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>

                    <?php endwhile; ?>

                <?php endif; ?>
            </div>

        </main>
    </div>
</div>

<!-- FOOTER -->
<footer class="text-center text-black-50 py-2">
    PHP_2025
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Activity chart JS -->
<script src="assets/js/activity_chart.js"></script>
</body>
</html>
