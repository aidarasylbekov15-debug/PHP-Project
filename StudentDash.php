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

$enrollmentQuery = $conn->prepare("
    SELECT e.group_id, e.course_id, g.name AS group_name, c.title AS course_title, u.full_name AS teacher_name, u.mobile
    FROM Enrollments e
    INNER JOIN Groups g ON e.group_id = g.id
    INNER JOIN Courses c ON e.course_id = c.id
    INNER JOIN Users u ON c.teacher_id = u.id
    WHERE e.student_id = ?
    LIMIT 1
");
$enrollmentQuery->bind_param("i", $studentId);
$enrollmentQuery->execute();
$enrollmentResult = $enrollmentQuery->get_result();
$enrolled = $enrollmentResult->fetch_assoc();
$enrollmentQuery->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>

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
        <button class="btn btn-outline-light dropdown-toggle w-100"
            style="min-width: 550px;" data-bs-toggle="dropdown">
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
            <a class="sidebar-btn active" href="StudentDash.php">Dashboard</a>
            <a class="sidebar-btn" href="ProfileStudent.php">Profile</a>
            <a class="sidebar-btn" href="LessonsStudent.php">Lessons</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">
            <h1 class="mb-4">Dashboard</h1>

<?php
if ($enrolled) {
    ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($enrolled['course_title']) ?> - <?= htmlspecialchars($enrolled['group_name']) ?></h5>
            <p class="card-text">Teacher: <?= htmlspecialchars($enrolled['teacher_name']) ?> | Mobile: <?= htmlspecialchars($enrolled['mobile']) ?></p>
            <a href="process_leaveGroup.php?group_id=<?= urlencode($enrolled['group_id']) ?>" class="btn btn-danger btn-sm">Leave</a>
        </div>
    </div>

    <?php
    // Получаем уроки для этой группы
    $lessonsQuery = $conn->prepare("
        SELECT * FROM Lessons
        WHERE group_id = ?
        ORDER BY lesson_date DESC
    ");
    $lessonsQuery->bind_param("s", $enrolled['group_id']);
    $lessonsQuery->execute();
    $lessonsResult = $lessonsQuery->get_result();

    if ($lessonsResult->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Title</th><th>Group</th><th>Content</th><th>Date</th><th>Add Answer</th></tr></thead><tbody>';
        while ($l = $lessonsResult->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($l['title']).'</td>';
            echo '<td>'.htmlspecialchars($enrolled['group_name']).'</td>';
            echo '<td><a href="'.htmlspecialchars($l['content']).'" target="_blank">Download/View</a></td>';
            echo '<td>'.htmlspecialchars($l['lesson_date']).'</td>';
            echo '<td>
                <form method="POST" action="process_addAnswer.php" enctype="multipart/form-data">
                    <input type="hidden" name="lesson_id" value="'.htmlspecialchars($l['id']).'">
                    <input type="file" name="task_file" required class="form-control form-control-sm mb-1">
                    <button type="submit" class="btn btn-success btn-sm">Add Answer</button>
                </form>
            </td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No lessons available for your group yet.</p>";
    }

} else {
    // Студент не состоит ни в одной группе — показываем все доступные группы
    $groupsResult = $conn->query("
        SELECT g.id AS group_id, g.name AS group_name, c.id AS course_id, c.title AS course_title, u.full_name AS teacher_name
        FROM Groups g
        INNER JOIN Courses c ON g.course_id = c.id
        INNER JOIN Users u ON c.teacher_id = u.id
    ");

    if ($groupsResult->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Course</th><th>Group</th><th>Teacher</th><th>Action</th></tr></thead><tbody>';
        while ($g = $groupsResult->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($g['course_title']).'</td>';
            echo '<td>'.htmlspecialchars($g['group_name']).'</td>';
            echo '<td>'.htmlspecialchars($g['teacher_name']).'</td>';
            echo '<td>
                <form method="POST" action="process_joinGroup.php">
                    <input type="hidden" name="group_id" value="'.htmlspecialchars($g['group_id']).'">
                    <input type="hidden" name="course_id" value="'.htmlspecialchars($g['course_id']).'">
                    <button type="submit" class="btn btn-primary btn-sm">Join</button>
                </form>
            </td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No groups available to join.</p>";
    }
}
?>
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
