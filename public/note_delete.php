<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/notes.php';

$n = new notes($pdo);

$n->delete($_GET['id'], $_SESSION['user_id']);

header("Location: notes.php");
exit;
