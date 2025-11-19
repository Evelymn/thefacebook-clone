<?php
// index.php - thefacebook 2004 authentic style
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
    <title>Welcome to Thefacebook</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container {
            width: 760px;
            margin: 0 auto;
        }
        
        .main-content {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .left-column {
            display: table-cell;
            width: 180px;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .right-column {
            display: table-cell;
            vertical-align: top;
            padding-left: 10px;
        }
        
        .login-box {
            border: 1px dotted #999999;
            padding: 10px;
            background-color: #ffffff;
            text-align: center;
        }
        
        .login-box table {
            margin: 0 auto;
        }
        
        .login-box td {
            padding: 3px;
            font-size: 11px;
        }
        
        .login-box input[type="text"],
        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 120px;
            padding: 2px;
            border: 1px solid #BDC7D8;
            font-size: 11px;
        }
        
        .login-box input[type="submit"],
        .login-box input[type="button"] {
            padding: 2px 8px;
            font-size: 10px;
            cursor: pointer;
            margin: 2px;
        }
        
        .welcome-box {
            border: 1px solid #CCCCCC;
            padding: 15px;
            background-color: #F7F7F7;
        }
        
        .welcome-box h1 {
            font-size: 16px;
            margin: 0 0 10px 0;
            text-align: center;
        }
        
        .welcome-box p {
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .welcome-box ul {
            margin-left: 20px;
            line-height: 1.8;
        }
        
        .welcome-box .buttons {
            text-align: center;
            margin-top: 15px;
        }
        
        .welcome-box .buttons input {
            padding: 3px 15px;
            font-size: 11px;
            margin: 0 5px;
        }
    </style>
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
            <div style="clear:both;"></div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <!-- Left Column - Login Form -->
            <div class="left-column">
                <div class="login-box">
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="right"><b>Email:</b></td>
                            </tr>
                            <tr>
                                <td><input type="email" name="email" required 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <td align="right"><b>Password:</b></td>
                            </tr>
                            <tr>
                                <td><input type="password" name="password" required></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <input type="button" value="register" onclick="window.location.href='register.php'">
                                    <input type="submit" value="login">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <!-- Right Column - Welcome Message -->
            <div class="right-column">
                <div class="welcome-box">
                    <h1>[ Welcome to Thefacebook ]</h1>
                    
                    <p>Thefacebook is an online directory that connects people through social networks at colleges.</p>
                    
                    <p>We have opened up Thefacebook for popular consumption at <b>Harvard University</b>.</p>
                    
                    <p>You can use Thefacebook to:</p>
                    <ul>
                        <li>Search for people at your school</li>
                        <li>Find out who are in your classes</li>
                        <li>Look up your friends' friends</li>
                        <li>See a visualization of your social network</li>
                    </ul>
                    
                    <p>To get started, click below to register. If you have already registered, you can log in.</p>
                    
                    <div class="buttons">
                        <input type="button" value="Register" onclick="window.location.href='register.php'">
                        <input type="button" value="Login" onclick="window.location.href='#'" 
                               style="background-color: #4C66A4; color: white; border: 1px solid #29447E;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="max-width: 760px; margin: 0 auto;">
            <a href="contact.php">about</a>
            <a href="#">contact</a>
            <a href="#">faq</a>
            <a href="#">terms</a>
            <a href="#">privacy</a>
            <br>
            a Mark Zuckerberg production
            <br>
            Thefacebook &copy; 2004
        </div>
    </div>
</body>
</html>