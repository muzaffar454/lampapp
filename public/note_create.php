<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/notes.php';

$n = new notes($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n->create($_SESSION['user_id'], $_POST['title'], $_POST['body']);
    header("Location: notes.php");
    exit;
}
?>

<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>create note</h2>

<form method="post">
    title: <input type="text" name="title"><br><br>
    body:<br>
    <textarea name="body" rows="5" cols="40"></textarea><br><br>
    <button type="submit">save</button>
</form>

<?php include __DIR__ . '/../templates/footer.php'; ?>
