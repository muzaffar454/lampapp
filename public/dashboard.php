<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>dashboard</h2>

<p>welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>

<p>this is your protected dashboard page.</p>

<a href="logout.php">logout</a>

<?php include __DIR__ . '/../templates/footer.php'; ?>

<p><a href="notes.php">manage your notes</a></p>
