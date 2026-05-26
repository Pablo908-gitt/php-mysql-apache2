<?php
require_once 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $errors = [];
    if (strlen($username) < 3) {
        $errors[] = "Логін має бути не менше 3 символів";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email некоректний";
    }
    if (strlen($password) < 6) {
        $errors[] = "Пароль має бути не менше 6 символів";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Паролі не співпадають";
    }
    if (empty($errors)) {
        try {
            $statement = $pdo->prepare("SELECT id FROM users WHERE username=? OR email=?");
            $statement->execute([$username, $email]);
            if ($statement->rowCount() > 0) {
                $message = "Користувач вже існує";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $statement = $pdo->prepare("INSERT INTO users (username,email,password_hash) VALUES (?,?,?)");
                $statement->execute([$username, $email, $password_hash]);
                $message = "Реєстрація успішна! <a href='login.php'>Увійти</a>";
            }
        } catch (PDOException $exception) {
            $message = "Помилка БД";
        }
    } else {
        $message = implode("<br>", $errors);
    }
}
?>
<h1>Реєстрація</h1>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Логін" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Пароль" required><br><br>
    <input type="password" name="confirm_password" placeholder="Підтвердження паролю" required><br><br>
    <button type="submit">Зареєструватися</button>
</form>
<p>Вже маєте акаунт? <a href="login.php">Увійти</a></p>
