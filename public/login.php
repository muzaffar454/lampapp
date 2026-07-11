<?php
session_start();

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/user.php';

$u = new user($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $u->login($_POST['email'], $_POST['password']);

    if ($result) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_name'] = $result['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "invalid login";
    }
}
?>

<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>login</h2>

<?php if (!empty($error)) echo "<p>$error</p>"; ?>

<form method="post">
    email: <input type="email" name="email"><br><br>
    password: <input type="password" name="password"><br><br>
    <button type="submit">login</button>
</form>

<br>

<a href="register.php">register</a>

<?php include __DIR__ . '/../templates/footer.php'; ?>
