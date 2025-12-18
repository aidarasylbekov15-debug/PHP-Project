<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: SignIn.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fullName  = $_SESSION['user_name'];
$teacherId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assignment_id'])) {

    $assignmentId = (int)$_POST['assignment_id'];
    $grade        = (int)$_POST['grade'];
    $comment      = $_POST['teacher_comment'];

    $stmt = $conn->prepare("
        UPDATE Assignments
        SET grade = ?, teacher_comment = ?, status = 'checked'
        WHERE id = ?
    ");
    $stmt->bind_param("isi", $grade, $comment, $assignmentId);
    $stmt->execute();

    header("Location: AssignmentGrade.php");
    exit;
}


$search = $_GET['search'] ?? '';
$searchLike = "%$search%";


$stmt = $conn->prepare("
    SELECT 
        a.id AS assignment_id,
        a.lesson_id,
        a.task,
        a.submitted_at,
        a.status,
        a.grade,
        a.teacher_comment,
        u.full_name
    FROM Assignments a
    JOIN Users u ON a.student_id = u.id
    JOIN Lessons l ON a.lesson_id = l.id
    JOIN Groups g ON l.group_id = g.id
    JOIN Courses c ON g.course_id = c.id
    WHERE c.teacher_id = ?
      AND u.full_name LIKE ?
    ORDER BY a.submitted_at DESC
");

$stmt->bind_param("is", $teacherId, $searchLike);
$stmt->execute();
$assignments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Teacher Assignment</title>

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
            <a class="sidebar-btn" href="TeacherDash.php">Dashboard</a>
            <a class="sidebar-btn" href="ProfileTeacher.php">Profile</a>
            <a class="sidebar-btn" href="NoteAdd.php">Lesson Notes</a>
            <a class="sidebar-btn active" href="AssignmentGrade.php">Assignments</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">

            <h1 class="mb-4">Assignment</h1>
            <label class="mb-4">Teacher . Assignment</label>

            <form method="GET" class="mb-3">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by student name"
                       value="<?= htmlspecialchars($search) ?>">
            </form>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Lesson ID</th>
                            <th>Student</th>
                            <th>Task</th>
                            <th>Submitted At</th>
                            <th>Status</th>
                            <th>Grade</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if ($assignments->num_rows === 0): ?>
                        <tr>
                            <td colspan="8" class="text-center">No assignments found</td>
                        </tr>
                    <?php endif; ?>

                    <?php while ($row = $assignments->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $row['lesson_id'] ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($row['task']) ?>" class="btn btn-sm btn-primary" download>
                                        Download
                                    </a>
                                </td>
                                <td><?= $row['submitted_at'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $row['status'] === 'checked' ? 'success' : 'warning' ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <input type="number" name="grade" class="form-control"
                                           min="0" max="100"
                                           value="<?= $row['grade'] ?>">
                                </td>
                                <td>
                                    <textarea name="teacher_comment" class="form-control"><?= htmlspecialchars($row['teacher_comment']) ?></textarea>
                                </td>
                                <td>
                                    <input type="hidden" name="assignment_id" value="<?= $row['assignment_id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Checked
                                    </button>
                                </td>
                            </form>
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
