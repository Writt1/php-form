<?php


session_start();

require __DIR__ . '/../app/Database.php';
require __DIR__ . '/../app/User.php';

use App\Database;
use App\User;


$db = new Database();
$user = new User($db);

$name  = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$pass1 = $_POST['password'] ?? '';
$pass2 = $_POST['password_confirmation'] ?? '';

$errors = [];
if ($pass1 !== $pass2) $errors[] = 'Пароли не совпадают';
if ($user->existsByEmail($email)) $errors[] = 'Email уже используется';
if ($user->existsByPhone($phone)) $errors[] = 'Телефон уже используется';

if ($errors) {
    $_SESSION['register_errors'] = $errors;
    header('Location: register.php');
    exit;
}

$id = $user->create($name, $phone, $email, $pass1);
$_SESSION['user_id'] = $id;
header('Location: profile.php');
exit;