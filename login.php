=== login.php ===
<?php
session_start();
require_once 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
        $message = "Заповніть всі поля";
    } else {
        try {
            $statement = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username=?");
            $statement->execute([$username]);
            $user = $statement->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $statement = $pdo->prepare("INSERT INTO login_attempts (ip_address, username_tried) VALUES (?,?)");
                $statement->execute([$ip_address, $username]);
                $message = "Невірний логін або пароль";
            }
        } catch (PDOException $exception) {
            $message = "Помилка сервера";
        }
    }
}
?>
<h1>Вхід</h1>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Логін" required><br><br>
    <input type="password" name="password" placeholder="Пароль" required><br><br>
    <button type="submit">Увійти</button>
</form>
<p>Немає акаунту? <a href="register.php">Реєстрація</a></p>
