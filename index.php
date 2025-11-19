<?php
// index.php - Landing Page EXACTO thefacebook 2004
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
</head>
<body>
    <!-- INCLUIR HEADER REUTILIZABLE -->
    <?php include 'includes/header.php'; ?>

    <!-- CONTENEDOR PRINCIPAL CON BORDE -->
    <div class="container">
        <div class="outer-box">
            <!-- (removed inner blue page-header to match requested layout) -->
            <!-- CONTENIDO EN 2 COLUMNAS (UN SOLO PANEL) -->
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <!-- COLUMNA IZQUIERDA: Login Form -->
                    <td class="sidebar">
                        <div class="login-panel">
                        <form method="POST" action="">
                            <table cellpadding="2" cellspacing="0">
                                <tr class="input-row">
                                    <td align="right"><b>Email:</b></td>
                                </tr>
                                <tr class="input-row">
                                    <td><input type="email" name="email" required class="input-field"
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"></td>
                                </tr>
                                <tr class="input-row">
                                    <td align="right"><b>Password:</b></td>
                                </tr>
                                <tr class="input-row">
                                    <td><input type="password" name="password" required class="input-field"></td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="register.php" class="btn btn-ghost" style="margin-right:6px;">register</a>
                                        <input type="submit" value="login" class="btn btn-primary">
                                    </td>
                                </tr>
                            </table>

                            <?php if ($error): ?>
                                <div class="error-message">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                        </form>
                        </div>
                    </td>

                    <!-- COLUMNA DERECHA: Welcome Message -->
                    <td class="content">
                        <div class="content-box">
                        <!-- BARRA AZUL INTERNA -->
                        <div style="background-color: #3B5998; color: #ffffff; padding: 6px 10px; 
                                    font-weight: bold; margin-bottom: 12px; font-size: 11px;">
                            Welcome to Thefacebook!
                        </div>

                        <h1 style="font-size: 16px; text-align: center; margin-bottom: 15px; font-weight: bold;">
                            [ Welcome to Thefacebook ]
                        </h1>

                        <p style="margin-bottom: 10px; font-size: 11px; line-height: 1.6;">
                            Thefacebook is an online directory that connects people through social networks at colleges.
                        </p>

                        <p style="margin-bottom: 10px; font-size: 11px; line-height: 1.6;">
                            We have opened up Thefacebook for popular consumption at <b>Harvard University</b>.
                        </p>

                        <p style="margin-bottom: 5px; font-size: 11px; line-height: 1.6;">
                            You can use Thefacebook to:
                        </p>
                        <ul style="margin-left: 20px; margin-bottom: 10px; font-size: 11px; line-height: 1.8;">
                            <li>Search for people at your school</li>
                            <li>Find out who are in your classes</li>
                            <li>Look up your friends' friends</li>
                            <li>See a visualization of your social network</li>
                        </ul>

                        <p style="margin-bottom: 15px; font-size: 11px; line-height: 1.6;">
                            To get started, click below to register. If you have already registered, you can log in.
                        </p>

                        <div style="text-align: center;">
                            <a href="register.php" class="btn btn-primary">Register</a>
                            <a href="#" onclick="document.querySelector('input[type=email]').focus()" class="btn btn-primary">Login</a>
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
        <a href="#">faq</a>
        <a href="#">terms</a>
        <a href="#">privacy</a>
        <br>
        a Mark Zuckerberg production
        <br>
        Thefacebook &copy; 2004
    </div>
</body>
</html>