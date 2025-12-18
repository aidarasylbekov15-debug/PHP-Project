<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: SignIn.php");
    exit;
}

$fullName = $_SESSION['user_name'];
$teacherId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$groupsSql = "
    SELECT g.id
    FROM Groups g
    JOIN Courses c ON g.course_id = c.id
    WHERE c.teacher_id = ?
";
$stmt = $conn->prepare($groupsSql);
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$groupsResult = $stmt->get_result();

// Lesson list
$lessonsSql = "
    SELECT id, group_id, title, lesson_date
    FROM Lessons
    WHERE created_by = ?
    ORDER BY lesson_date DESC
";
$stmt = $conn->prepare($lessonsSql);
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$lessonsResult = $stmt->get_result();

$selectedGroup = $_GET['group_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Teacher Lesson Notes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .sidebar {
            background-color: #0D6EFD1A;
            min-height: 100%;
        }
        .sidebar-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #000;
            border: 1px solid #000;
            border-radius: 6px;
        }
        .sidebar-btn:hover,
        .sidebar-btn.active {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark bg-dark px-4 py-4">
    <span class="navbar-brand">Courses Management System</span>
    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" style="min-width: 550px;">
            <?= htmlspecialchars($fullName) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="ProfileTeacher.php">Profile</a></li>
            <li><a class="dropdown-item" href="TeacherDash.php">Dashboard</a></li>
            <li><a class="dropdown-item text-danger" href="SignOut.php">Sign Out</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid flex-grow-1">
<div class="row">

<aside class="col-3 sidebar p-3">
    <a class="sidebar-btn" href="TeacherDash.php">Dashboard</a>
    <a class="sidebar-btn" href="ProfileTeacher.php">Profile</a>
    <a class="sidebar-btn active" href="NoteAdd.php">Lesson Notes</a>
    <a class="sidebar-btn" href="AssignmentGrade.php">Assignments</a>
    <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
</aside>

<main class="col-9 p-4">
    <h1 class="mb-4">Lesson Notes</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Add Lesson</h5>

            <form action="process_addNote.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Group</label>
                    <select name="group_id" class="form-control" required>
                        <option value="">-- Select Group --</option>
                        <?php while ($g = $groupsResult->fetch_assoc()): ?>
                            <option value="<?= $g['id'] ?>"
                                <?= ($g['id'] === $selectedGroup) ? 'selected' : '' ?>>
                                <?= $g['id'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Lesson Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Lesson Date</label>
                    <input type="date" name="lesson_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <input type="file"
                        name="content"
                        class="form-control"
                        accept=".pdf,.doc,.docx,.ppt,.pptx">
                </div>


                <button type="submit" name="add_lesson" class="btn btn-success">
                    Add Lesson
                </button>
            </form>
        </div>
    </div>

    <!-- LESSON LIST -->
    <div class="card">
        <div class="card-body">
            <h5>My Lessons</h5>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Group</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($lessonsResult->num_rows === 0): ?>
                    <tr><td colspan="4" class="text-center">No lessons</td></tr>
                    <?php else: ?>
                    <?php while ($l = $lessonsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $l['group_id'] ?></td>
                        <td><?= htmlspecialchars($l['title']) ?></td>
                        <td><?= $l['lesson_date'] ?></td>
                        <td>
                            <a href="process_addNote.php?delete=<?= $l['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete lesson?')">
                            Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>
</div>
</div>

<footer class="text-center py-2 text-black-50">PHP_2025</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
