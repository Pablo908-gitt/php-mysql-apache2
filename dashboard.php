<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$username = htmlspecialchars($_SESSION['username']);
$session_id = session_id();
?>
<h1>Захищена панель</h1>
<p>Вітаю, <?= $username ?>!</p>
<p>ID сесії: <?= $session_id ?></p>
<ul>
    <li>BCRYPT — хешування паролів</li>
    <li>Prepared Statements — захист від SQL-ін'єкцій</li>
    <li>Session Regenerate — захист від Session Fixation</li>
    <li>Логування невдалих спроб входу</li>
</ul>
<a href="logout.php">Вийти</a>
