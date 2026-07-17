<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $plain_password = $_POST['password'] ?? '';

    if (!empty($user) && !empty($email) && !empty($plain_password)) {
        try {
            $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([
                ':username' => $user,
                ':email'    => $email,
                ':password' => $hashed_password
            ]);

            $message = "<div style='color: green; font-weight: bold; margin-bottom: 15px;'>Registration successful! <a href='login.php'>Login here</a></div>";
        } catch (PDOException $e) {
            $message = "<div style='color: red; margin-bottom: 15px;'>Registration failed: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Please fill in all empty fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Application Account</title>
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
        button { background-color: #4CAF50; color: white; padding: 12px 15px; border: none; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #45a049; }
        .nav-link { margin-top: 20px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <?php echo $message; ?>
        <form action="register.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Email Address:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Register Account</button>
        </form>
        <div class="nav-link">
            Already have an account? <a href="login.php" style="color: #0066cc; font-weight: bold; text-decoration: none;">Login here</a>
        </div>
    </div>
</body>
</html>

