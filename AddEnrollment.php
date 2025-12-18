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

$coursesResult = $conn->query("SELECT * FROM Courses");

$groupsResult = $conn->query("SELECT * FROM Groups");

$studentsResult = $conn->query("SELECT id, full_name FROM Users WHERE user_role='student'");

$groupsJS = [];
while($group = $groupsResult->fetch_assoc()) {
    $groupsJS[] = $group;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Student Enrollment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .sidebar { background-color: #0D6EFD1A; min-height: 100%; font-size: 1.2rem; }
        .sidebar-btn { display: flex; justify-content: center; align-items: center; padding: 12px 14px; margin-bottom: 10px; text-decoration: none; color: #000; border-radius: 6px; border: 1px solid #000; font-size: 1rem; font-weight: 400; text-align: center; }
        .sidebar-btn:hover, .sidebar-btn.active { background-color: #000; color: #fff; }
        .student-row:hover { background-color: #f1f1f1; }
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
            <a class="sidebar-btn" href="ManageGroup.php">Group Manage</a>
            <a class="sidebar-btn active" href="StudentEnrollment.php">Student Enrollment</a>
            <a class="sidebar-btn text-danger" href="SignOut.php">Sign Out</a>
        </aside>

        <main class="col-9 p-4">
            <h1 class="mb-4">Add Enrollment</h1>
            <label class="mb-4">Admin . Add Enrollment</label>

            <form method="post" action="process_addEnrollment.php">

                <div class="mb-3">
                    <label for="course_id" class="form-label">Course</label>
                    <select name="course_id" id="course_id" class="form-select" required onchange="filterGroups(); updateCourseName();">
                        <option value="">Select Course</option>
                        <?php while($course = $coursesResult->fetch_assoc()): ?>
                            <option value="<?= $course['id'] ?>" data-title="<?= htmlspecialchars($course['title']) ?>"><?= $course['id'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <small id="courseTitle" class="form-text text-muted"></small>
                </div>

                <div class="mb-3">
                    <label for="group_id" class="form-label">Group</label>
                    <select name="group_id" id="group_id" class="form-select" required>
                        <option value="">Select Group</option>
                    </select>
                    <small id="groupName" class="form-text text-muted"></small>
                </div>

                

                <div class="mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <input type="hidden" name="student_id" id="student_id" required>
                    <input type="text" id="student_search" class="form-control mb-2" placeholder="Search student...">
                    
                    <button type="submit" class="btn btn-primary mb-3">Add</button>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="student_table">
                        <?php while($student = $studentsResult->fetch_assoc()): ?>
                            <tr class="student-row">
                                <td><?= htmlspecialchars($student['full_name']) ?></td>
                                <td><button type="button" class="btn btn-sm btn-success" onclick="selectStudent(<?= $student['id'] ?>, '<?= htmlspecialchars(addslashes($student['full_name'])) ?>')">Select</button></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div id="selectedStudent" class="mt-2"></div>
                </div>

            </form>
        </main>
    </div>
</div>

<footer class="text-center text-black-50 py-2">
    PHP_2025
</footer>

<script>
const allGroups = <?= json_encode($groupsJS) ?>;

function selectStudent(id, name) {
    document.getElementById('student_id').value = id;
    document.getElementById('selectedStudent').innerHTML = 'Selected Student: <strong>' + name + '</strong>';
}


document.getElementById('student_search').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#student_table tr');
    rows.forEach(row => {
        let name = row.cells[0].innerText.toLowerCase();
        row.style.display = name.includes(filter) ? '' : 'none';
    });
});


function updateCourseName() {
    let select = document.getElementById('course_id');
    let title = select.selectedOptions[0]?.dataset.title || '';
    document.getElementById('courseTitle').innerText = title ? 'Course Title: ' + title : '';
}


function updateGroupName() {
    let select = document.getElementById('group_id');
    let name = select.selectedOptions[0]?.dataset.name || '';
    document.getElementById('groupName').innerText = name ? 'Group Name: ' + name : '';
}


function filterGroups() {
    let courseId = document.getElementById('course_id').value;
    let groupSelect = document.getElementById('group_id');


    let selectedGroup = groupSelect.value;


    groupSelect.innerHTML = '<option value="">Select Group</option>';

    allGroups.forEach(group => {
        if(group.course_id === courseId) {
            let opt = document.createElement('option');
            opt.value = group.id;
            opt.dataset.name = group.name;
            opt.textContent = group.id;
            groupSelect.appendChild(opt);
        }
    });


    if(selectedGroup) groupSelect.value = selectedGroup;

    updateGroupName();
}
</script>
</body>
</html>
