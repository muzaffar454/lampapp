<?php
session_start();

require __DIR__ . '/../templates/header.php';
?>

<h2>welcome to my app</h2>

<h3>login</h3>

<form method="post" action="login.php">
    email: <input type="email" name="email"><br><br>
    password: <input type="password" name="password"><br><br>
    <button type="submit">login</button>
</form>

<br>

<a href="register.php">register</a>

<?php
require __DIR__ . '/../templates/footer.php';
?>
