<?php
session_start();
$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column" style="min-height:100vh; background:#f4f6f8;">

<div class="container flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">

        <div class="text-center mb-3">
            <label class="fs-2 fw-bold">Courses Management System</label>
        </div>

        <h5 class="text-center mb-3">Регистрация</h5>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="process_Register.php">

            <div class="mb-3">
                <input type="text" name="full_name" class="form-control"
                       placeholder="Full name" required>
            </div>

            <div class="mb-3">
                <input type="text" name="mobile" class="form-control"
                       placeholder="Mobile">
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control"
                       placeholder="E-mail" required>
            </div>

            <div class="mb-3">
                <input type="text" name="address" class="form-control"
                       placeholder="Address">
            </div>

            <div class="mb-3">
                <select name="city" class="form-select" required>
                    <option value="">Select city</option>
                    <option>Bishkek</option>
                    <option>Osh</option>
                    <option>Toktogul</option>
                </select>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control"
                       placeholder="Password" required>
            </div>

            <div class="mb-3">
                <select name="gender" class="form-select" required>
                    <option value="">Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100 mb-2">
                Зарегистрироваться
            </button>
        </form>

        <a href="SignIn.php" class="btn btn-outline-secondary w-100">
            Уже зарегистрированы? Войти
        </a>

    </div>
</div>

<footer class="text-center text-black-50 py-2">
    <label>PHP_2025</label>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
