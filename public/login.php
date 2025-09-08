<?php
session_start();


if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}


$errors = $_SESSION['login_errors'] ?? [];
unset($_SESSION['login_errors']);
?>


<?php if ($errors): ?>
    <div style="color: red;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>

<form method="POST" action="do_login.php">
    <div>
        <label for="login">Email или телефон</label>
        <input type="text" id="login" name="login" required>
    </div>

    <div>
        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div
            id="captcha-container"
            class="smart-captcha"
            data-sitekey="ysc1_dyhsjgywe76dgY1oGIUVX1rQSekdLHG9pNGDIBYTef9df8be"
            style="height: 100px"
    >
        <input type="hidden" name="smart-token" value="">
    </div>



    <button>Войти</button>

    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a> </p>
</form>

