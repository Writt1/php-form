<?php
session_start();
require __DIR__ . '/../app/Database.php';
require __DIR__ . '/../app/User.php';
require __DIR__ . '/../config.php';

use App\Database;
use App\User;

$db = new Database();
$user = new User($db);

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

define('SMARTCAPTCHA_SERVER_KEY', getenv('SMARTCAPTCHA_SERVER_KEY'));

$token = $_POST['smart-token'] ?? '';
$userIp = $_SERVER['REMOTE_ADDR'];

function check_captcha($token, $userIp) {
    $ch = curl_init("https://smartcaptcha.yandexcloud.net/validate");
    $args = [
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $userIp,
    ];
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        return true;
    }

    $resp = json_decode($server_output);
    return $resp->status === "ok";
}

if (!check_captcha($token, $userIp)) {
    $_SESSION['login_errors'] = ['Капча не пройдена'];
    header('Location: login.php');
    exit;
}


$found = $db->findOne('users', ['email' => $login])
    ?? $db->findOne('users', ['phone' => $login]);

if (!$found) {
    $errors[] = 'Пользователь не найден';
} elseif (!password_verify($password, $found['password'])) {
    $errors[] = 'Неверный пароль';
}

if ($errors) {
    $_SESSION['login_errors'] = $errors;
    header('Location: login.php');
    exit;
}



$_SESSION['user_id'] = $found['id'];
header('Location: profile.php');
exit;
