<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
session_start();

// Block unauthenticated system entry strings
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle writing a new note item to the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!empty($title) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (:user_id, :title, :content)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':title'   => $title,
                ':content' => $content
            ]);
            $message = "<div style='color: green; font-weight: bold; margin-bottom: 15px;'>Note added successfully!</div>";
        } catch (PDOException $e) {
            $message = "<div style='color: red; margin-bottom: 15px;'>Failed to save note: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}

// Retrieve existing items associated with this specific user account
try {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute([':user_id' => $user_id]);
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Failed to fetch user notes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            background-color: #f9f9f9; 
            margin: 0; 
            padding: 0;
        }
        /* Top navigation header spanning full width */
        .navbar {
            background-color: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 { margin: 0; font-size: 20px; }
        .logout-btn { 
            color: white; 
            text-decoration: none; 
            background-color: #d9534f; 
            padding: 8px 15px; 
            border-radius: 4px; 
            font-weight: bold;
        }
        .logout-btn:hover { background-color: #c9302c; }
        
        /* Central layout container */
        .main-content {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
            box-sizing: border-box;
        }
        
        /* Card styles for input form and listed notes */
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .card h3 { margin-top: 0; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        label { font-weight: bold; color: #555; }
        input[type="text"], textarea { 
            width: 100%; 
            padding: 10px; 
            margin: 8px 0 20px 0; 
            box-sizing: border-box; 
            border: 1px solid #ccc; 
            border-radius: 4px;
            font-family: Arial, sans-serif;
        }
        button { 
            background-color: #0066cc; 
            color: white; 
            padding: 12px 15px; 
            border: none; 
            width: 100%; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
        }
        button:hover { background-color: #0052a3; }
        
        /* Notes specific feed visual elements */
        .note-item { 
            border-left: 5px solid #0066cc; 
            background: #fbfbfb;
            padding: 15px; 
            margin-bottom: 15px; 
            border-radius: 0 4px 4px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .note-item h4 { margin: 0 0 10px 0; color: #222; font-size: 18px; }
        .note-item p { margin: 0 0 10px 0; color: #444; white-space: pre-line; }
        .note-item small { color: #888; font-size: 12px; }
        .no-notes { text-align: center; color: #777; font-style: italic; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="logout.php" class="logout-btn">Sign Out</a>
    </div>

    <div class="main-content">
        <!-- Centered Add Note Form Card -->
        <div class="card">
            <h3>Create a New Note</h3>
            <?php echo $message; ?>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="add_note" value="1">
                
                <label>Title:</label>
                <input type="text" name="title" required>
                
                <label>Content:</label>
                <textarea name="content" rows="5" required></textarea>
                
                <button type="submit">Save Note Entry</button>
            </form>
        </div>

        <!-- Centered Notes Feed Card -->
        <div class="card">
            <h3>Your Saved Notes</h3>
            <?php if (empty($notes)): ?>
                <p class="no-notes">No notes found. Create your first one above!</p>
            <?php else: ?>
                <?php foreach ($notes as $note): ?>
                    <div class="note-item">
                        <h4><?php echo htmlspecialchars($note['title']); ?></h4>
                        <p><?php echo htmlspecialchars($note['content']); ?></p>
                        <small>Created on: <?php echo $note['created_at']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

