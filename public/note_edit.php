<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/notes.php';

$n = new notes($pdo);

$note = $n->get_one($_GET['id'], $_SESSION['user_id']);

if (!$note) {
    die("note not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n->update($_GET['id'], $_SESSION['user_id'], $_POST['title'], $_POST['body']);
    header("Location: notes.php");
    exit;
}
?>

<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>edit note</h2>

<form method="post">
    title: <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>"><br><br>
    body:<br>
    <textarea name="body" rows="5" cols="40"><?php echo htmlspecialchars($note['body']); ?></textarea><br><br>
    <button type="submit">update</button>
</form>

<?php include __DIR__ . '/../templates/footer.php'; ?>
