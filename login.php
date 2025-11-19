<?php
// login.php - thefacebook 2004 style
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, email, password, avatar FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar'];
                
                session_regenerate_id(true);
                
                header("Location: home.php");
                exit();
            } else {
                $error = "Incorrect email or password.";
            }
        } else {
            $error = "Incorrect email or password.";
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook | login</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2.0">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <a href="index.php" class="header-logo">thefacebook</a>
            <span class="header-links">
                <a href="login.php">login</a>
                <a href="register.php">register</a>
                <a href="contact.php">about</a>
            </span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="login-container">
        <!-- Login Box -->
        <div class="login-box">
            <h1>Login</h1>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="login-form">
                <form method="POST" action="">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td><b>Email:</b></td>
                            <td><input type="email" name="email" required 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td><b>Password:</b></td>
                            <td><input type="password" name="password" required></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right">
                                <input type="submit" value="Login">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <!-- Info Box -->
        <div class="login-info">
            <h2>About thefacebook</h2>
            <p>thefacebook is an online directory that connects people through universities.</p>
            <br>
            <h2>Why thefacebook?</h2>
            <ul>
                <li>Connect with students at your university</li>
                <li>Share photos and information</li>
                <li>Build your university network</li>
            </ul>
            <br>
            <p><b>Don't have an account?</b> <a href="register.php">Sign Up</a></p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        thefacebook &copy; 2004 - University Project UVG
        <br>
        <a href="contact.php">about</a>
        <a href="#">terms</a>
        <a href="#">privacy</a>
    </div>
</body>
</html>
