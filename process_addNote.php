<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: SignIn.php");
    exit;
}

$teacherId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "CMS");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_lesson'])) {

    $groupId = $_POST['group_id'];
    $title = $_POST['title'];
    $lessonDate = $_POST['lesson_date'];

    $filePath = null;

    if (!empty($_FILES['content']['name'])) {

        $uploadDir = "uploads/lessons/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($_FILES['content']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $newFileName = uniqid("lesson_") . "." . $extension;
        $targetPath = $uploadDir . $newFileName;

        $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];
        if (!in_array(strtolower($extension), $allowed)) {
            die("Invalid file type");
        }

        if (move_uploaded_file($_FILES['content']['tmp_name'], $targetPath)) {
            $filePath = $targetPath;
        }
    }

    
    $sql = "
        INSERT INTO Lessons (group_id, title, content, lesson_date, created_by)
        VALUES (?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssi",
        $groupId,
        $title,
        $filePath,
        $lessonDate,
        $teacherId
    );
    $stmt->execute();

    header("Location: NoteAdd.php");
    exit;
}


if (isset($_GET['delete'])) {
    $lessonId = (int)$_GET['delete'];


    $getFile = $conn->prepare("
        SELECT content FROM Lessons
        WHERE id = ? AND created_by = ?
    ");
    $getFile->bind_param("ii", $lessonId, $teacherId);
    $getFile->execute();
    $result = $getFile->get_result();

    if ($row = $result->fetch_assoc()) {
        if (!empty($row['content']) && file_exists($row['content'])) {
            unlink($row['content']);
        }
    }

    $deleteSql = "
        DELETE FROM Lessons
        WHERE id = ? AND created_by = ?
    ";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("ii", $lessonId, $teacherId);
    $stmt->execute();

    header("Location: NoteAdd.php");
    exit;
}

header("Location: NoteAdd.php");
exit;
