<?php
session_start();
$errors = $_SESSION['register_errors'] ?? [];
unset($_SESSION['register_errors']);
?>

<?php if ($errors): ?>
    <div style="color: red;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="do_register.php">
    <div>
        <label for="name">Имя</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div>
        <label for="phone">Телефон</label>
        <input type="text" id="phone" name="phone" required>
    </div>

    <div>
        <label for="email">Почта</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div>
        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Повторите пароль</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <button>Создать аккаунт</button>

    <p>Есть аккаунта? <a href="login.php">Войти</a> </p>
</form>

