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
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-sidebar-box { padding: 0; }
        .login-section-title { 
            background-color: #3B5998; 
            color: #fff; 
            padding: 6px 8px; 
            font-weight: bold; 
            font-size: 11px; 
            margin-bottom: 8px;
        }
        .login-section-title a { color: #fff; text-decoration: none; }
        .login-section-title a:hover { text-decoration: underline; }
        .login-sidebar-section { 
            margin-bottom: 8px; 
            font-size: 11px;
            padding: 2px 0;
        }
        .login-sidebar-section a { display: block; padding: 2px 0; }
        .login-content-header {
            background: #3B5998;
            color: #fff;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
            text-align: center;
        }
        .login-form-box {
            border: 1px solid #ccc;
            padding: 15px;
            background: #fff;
            margin-bottom: 15px;
        }
        .login-form-box input[type="email"],
        .login-form-box input[type="password"] {
            width: 180px;
            padding: 4px;
            border: 1px solid #999;
            font-size: 11px;
            margin: 5px 0;
        }
        .login-form-box .btn-row {
            text-align: center;
            margin-top: 10px;
        }
        .login-form-box .btn-row input {
            padding: 5px 16px;
            margin: 0 4px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- INCLUIR HEADER REUTILIZABLE -->
    <?php include 'includes/header.php'; ?>

    <!-- Main container -->
    <div class="container">
        <div class="outer-box">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <!-- LEFT SIDEBAR with navigation -->
                    <td class="sidebar">
                        <div class="login-panel">
                        <div class="login-sidebar-box">
                            <div class="login-section-title">
                                <a href="#">main</a>
                            </div>

                            <div class="login-sidebar-section">
                                <a href="login.php">[ login ]</a>
                            </div>
                            
                            <div class="login-sidebar-section">
                                <a href="register.php">[ register ]</a>
                            </div>
                        </div>
                        </div>
                    </td>

                    <!-- RIGHT CONTENT -->
                    <td class="content">
                        <div class="content-box">
                            <div class="login-content-header">[ Login ]</div>

                            <?php if ($error): ?>
                                <div class="error-message">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>

                            <div class="login-form-box">
                                <form method="POST" action="">
                                    <div style="margin-bottom: 12px;">
                                        <label style="font-weight: bold; display: block; margin-bottom: 4px;">Email:</label>
                                        <input type="email" name="email" required 
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    </div>

                                    <div style="margin-bottom: 12px;">
                                        <label style="font-weight: bold; display: block; margin-bottom: 4px;">Password:</label>
                                        <input type="password" name="password" required>
                                    </div>

                                    <div class="btn-row">
                                        <input type="submit" value="Login" class="btn btn-primary">
                                        <a href="register.php" class="btn btn-primary" style="display: inline-block; padding: 5px 16px; text-decoration: none; margin: 0 4px;">Register</a>
                                    </div>
                                </form>
                            </div>

                            <div style="text-align: center; font-size: 11px; padding: 10px 0; border-top: 1px solid #ccc; margin-top: 15px;">
                                If you have forgotten your password, click <a href="#">here</a> to reset it.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <a href="contact.php">about</a>
        <a href="#">contact</a>
        <a href="#">jobs</a>
        <a href="#">announce</a>
        <a href="#">advertise</a>
        <a href="#">terms</a>
        <a href="#">privacy</a>
        <br>
        a Mark Zuckerberg production
        <br>
        Thefacebook &copy; 2005
    </div>
</body>
</html>
