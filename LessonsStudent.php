<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];
$studentId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("
    SELECT a.id, a.task, a.submitted_at, a.grade, a.status, a.teacher_comment,
           l.title AS lesson_title, g.name AS group_name
    FROM Assignments a
    JOIN Lessons l ON a.lesson_id = l.id
    JOIN Groups g ON l.group_id = g.id
    WHERE a.student_id = ?
    ORDER BY a.submitted_at DESC
");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Student Lessons</title>

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
            <a class="sidebar-btn" href="ProfileStudent.php">Profile</a>
            <a class="sidebar-btn active" href="LessonsStudent.php">Lessons</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">
            <h1 class="mb-4">Lessons</h1>
            <label class="mb-4">Student . Lessons</label>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Lesson</th>
                        <th>Group</th>
                        <th>Task File</th>
                        <th>Submitted At</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Teacher Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['lesson_title']) ?></td>
                                <td><?= htmlspecialchars($row['group_name']) ?></td>
                                <td>
                                    <?php if (!empty($row['task']) && file_exists($row['task'])): ?>
                                        <a href="<?= htmlspecialchars($row['task']) ?>" target="_blank">Download</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                                <td><?= htmlspecialchars($row['grade'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td><?= htmlspecialchars($row['teacher_comment'] ?? '-') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No assignments submitted yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

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

<?php
$stmt->close();
$conn->close();
?>
