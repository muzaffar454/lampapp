<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $message = "<div style='color: red; margin-bottom: 15px;'>Invalid email address or account password.</div>";
            }
        } catch (PDOException $e) {
            $message = "<div style='color: red; margin-bottom: 15px;'>Login failed: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Portal Access</title>
    <style>
        /* Forces the body to occupy full screen height and centers all contents layout */
        html, body { height: 100%; margin: 0; padding: 0; }
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            background-color: #f9f9f9; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }
        .container { 
            width: 100%;
            max-width: 400px; 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            box-sizing: border-box;
        }
        h2 { margin-top: 0; text-align: center; color: #333; }
        label { font-weight: bold; color: #555; }
        input { width: 100%; padding: 10px; margin: 8px 0 20px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #0066cc; color: white; padding: 12px 15px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #0052a3; }
        .nav-link { margin-top: 20px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Login</h2>
        <?php echo $message; ?>
        <form action="login.php" method="POST">
            <label>Email Address:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <div class="nav-link">
            Don't have an account yet? <a href="register.php" style="color: #4CAF50; font-weight: bold; text-decoration: none;">Register here</a>
        </div>
    </div>
</body>
</html>

