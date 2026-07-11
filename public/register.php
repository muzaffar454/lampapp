<?php
session_start();

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/user.php';

$u = new user($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u->register($_POST['name'], $_POST['email'], $_POST['password']);
    header("Location: login.php");
    exit;
}
?>

<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>register</h2>

<form method="post">
    name: <input type="text" name="name"><br><br>
    email: <input type="email" name="email"><br><br>
    password: <input type="password" name="password"><br><br>
    <button type="submit">register</button>
</form>

<br>

<a href="login.php">login</a>

<?php include __DIR__ . '/../templates/footer.php'; ?>
