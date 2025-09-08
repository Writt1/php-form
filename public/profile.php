<?php
session_start();
require __DIR__ . '/../app/Database.php';
require __DIR__ . '/../app/User.php';

use App\Database;
use App\User;

$db = new Database();
$user = new User($db);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$current = $db->findOne('users', ['id' => $_SESSION['user_id']]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    $errors = [];

    if ($password && $password !== $password_confirmation) {
        $errors[] = 'Пароли не совпадают';
    }

    $checkEmail = $db->findOne('users', ['email' => $email]);
    if ($checkEmail && $checkEmail['id'] != $_SESSION['user_id']) {
        $errors[] = 'Email уже используется';
    }

    $checkPhone = $db->findOne('users', ['phone' => $phone]);
    if ($checkPhone && $checkPhone['id'] != $_SESSION['user_id']) {
        $errors[] = 'Телефон уже используется';
    }

    if (!$errors) {
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $db->update('users', $data, ['id' => $_SESSION['user_id']]);
        header('Location: profile.php');
        exit;
    } else {
        var_dump($errors);
    }
}
?>

<h2>Профиль</h2>
<form method="POST" action="">
    <div>
        <input type="text" name="name" value="<?= htmlspecialchars($current['name']) ?>" required>
    </div>

    <div>
        <input type="text" name="phone" value="<?= htmlspecialchars($current['phone']) ?>" required>
    </div>

    <div>
        <input type="email" name="email" value="<?= htmlspecialchars($current['email']) ?>" required>
    </div>

    <div>
        <input type="password" name="password" placeholder="Новый пароль">
    </div>

    <div>
        <input type="password" name="password_confirmation" placeholder="Повтор пароля">
    </div>

    <button>Сохранить</button>
</form>

<a href="logout.php">Выйти</a>