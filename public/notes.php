<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/notes.php';

$n = new notes($pdo);
$notes = $n->get_all($_SESSION['user_id']);
?>

<?php include __DIR__ . '/../templates/header.php'; ?>
<a href="dashboard.php">go to dashboard</a><br><br>

<h2>your notes</h2>

<a href="note_create.php">create new note</a><br><br>

<?php foreach ($notes as $note): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3><?php echo htmlspecialchars($note['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($note['body'])); ?></p>
        <a href="note_edit.php?id=<?php echo $note['id']; ?>">edit</a> |
        <a href="note_delete.php?id=<?php echo $note['id']; ?>">delete</a>
    </div>
<?php endforeach; ?>

<?php include __DIR__ . '/../templates/footer.php'; ?>

