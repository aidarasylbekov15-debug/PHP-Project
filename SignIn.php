<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }
        body {
            background-color: #f4f6f8;
        }
    </style>
</head>
<body class="d-flex flex-column">

<div class="container flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">

        <div class="text-center mb-3">
            <label class="fs-2 fw-bold">Courses Management System</label>
        </div>

        <h4 class="text-center mb-3">Вход в систему</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="process_SignIn.php">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Пароль" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">
                Войти
            </button>
        </form>

        <form action="Register.php" method="get">
            <button type="submit" class="btn btn-outline-secondary w-100">
                Зарегистрироваться
            </button>
        </form>

    </div>
</div>

<footer class="text-center text-black-50 py-2">
    <label>PHP_2025</label>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
