<?php
// contact.php - About Page thefacebook 2004
session_start();

// Si no está logueado, procesar login desde sidebar
if (!isset($_SESSION['user_id'])) {
    require_once 'config/database.php';
    
    $login_error = '';
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_submit'])) {
        $email = sanitizeInput($_POST['login_email'] ?? '');
        $password = $_POST['login_password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $login_error = "Please fill in all fields.";
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
                    $login_error = "Incorrect email or password.";
                }
            } else {
                $login_error = "Incorrect email or password.";
            }
            
            $stmt->close();
            $conn->close();
        }
    }
}

$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook | about</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- INCLUIR HEADER REUTILIZABLE -->
    <?php include 'includes/header.php'; ?>

    <!-- CONTAINER -->
    <div class="container">
        <div class="outer-box">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <?php if (!$is_logged_in): ?>
                        <!-- SIDEBAR DE LOGIN (solo si NO está logueado) -->
                        <td class="sidebar">
                            <div class="login-panel">
                                <form method="POST" action="">
                                    <input type="hidden" name="login_submit" value="1">
                                    <table cellpadding="2" cellspacing="0">
                                        <tr class="input-row">
                                            <td align="right"><b>Email:</b></td>
                                        </tr>
                                        <tr class="input-row">
                                            <td><input type="email" name="login_email" required class="input-field"></td>
                                        </tr>
                                        <tr class="input-row">
                                            <td align="right"><b>Password:</b></td>
                                        </tr>
                                        <tr class="input-row">
                                            <td><input type="password" name="login_password" required class="input-field"></td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <a href="register.php" class="btn btn-ghost" style="margin-right:6px;">register</a>
                                                <input type="submit" value="login" class="btn btn-primary">
                                            </td>
                                        </tr>
                                    </table>

                                    <?php if (isset($login_error) && $login_error): ?>
                                        <div class="error-message">
                                            <?php echo $login_error; ?>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </td>
                    <?php endif; ?>

                    <!-- CONTENIDO PRINCIPAL -->
                    <td class="content">
                        <div class="content-box">
                            <!-- TÍTULO -->
                            <div class="login-content-header">About Thefacebook</div>

                           <div style="margin-bottom: 20px;">
    <h3 style="font-size: 13px; color: #3B5998; font-weight: bold; margin-bottom: 8px;">The Project</h3>
    <p style="line-height: 1.6; margin-bottom: 10px; font-size: 11px;">
        This website is a functional clone of <b>thefacebook</b>, the original version of Facebook launched in 2004. 
        The project recreates the appearance, structure, and basic features of the early platform, following the 
        historical references available through the Wayback Machine and public archives.
    </p>
    <p style="line-height: 1.6; margin-bottom: 10px; font-size: 11px;">
        The development was carried out using only <b>PHP</b>, <b>MySQL</b>, <b>HTML</b>, <b>CSS</b> and <b>JavaScript</b>, 
        according to the academic requirements for the Web Programming course at the 
        <b>Universidad del Valle de Guatemala (UVG)</b>.
    </p>
    <p style="line-height: 1.6; font-size: 11px;">
        The platform includes user registration limited to university emails, login validation, profile visualization, 
        session handling, and an interface faithful to the 2004 version of the site.
    </p>
</div>

<div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #EEEEEE;">
    <h3 style="font-size: 13px; color: #3B5998; font-weight: bold; margin-bottom: 8px;">The People</h3>
    <table cellpadding="0" cellspacing="0" width="100%" style="font-size: 11px;">
        <tr>
            <td width="50%" valign="top">
                <div style="margin-bottom: 8px;">
                    <b>Mark Zuckerberg</b><br>
                    <span style="font-size: 10px; color: #666;">Founder of thefacebook (2004).</span>
                </div>
                <div style="margin-bottom: 8px;">
                    <b>Dustin Moskovitz</b><br>
                    <span style="font-size: 10px; color: #666;">Co-founder.</span>
                </div>
                <div style="margin-bottom: 8px;">
                    <b>Chris Hughes</b><br>
                    <span style="font-size: 10px; color: #666;">Co-founder & early spokesperson.</span>
                </div>
            </td>
            <td width="50%" valign="top">
                <b>Original Contact (2004):</b><br>
                <a href="mailto:security@facebook.com">security@facebook.com</a><br>
                <span style="font-size: 10px; color: #666;">Security Department</span>
            </td>
        </tr>
    </table>
</div>

                         
<div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #EEEEEE;">
    <h3 style="font-size: 13px; color: #3B5998; font-weight: bold; margin-bottom: 8px;">UVG Project Creator</h3>
    <p style="margin-bottom: 10px; font-size: 10px; color: #666;">
        Developed as an academic project for the course “Programación Web”, Universidad del Valle de Guatemala.
    </p>
    
    <table cellpadding="5" cellspacing="0">
        <tr>
            <td valign="top" style="text-align: center; width: 120px;">
                <img src="assets/images/avatars/mi-foto.jpg" 
                     alt="Creator Photo" 
                     style="width: 100px; height: 100px; border: 1px solid #CCCCCC; object-fit: cover;"
                     onerror="this.src='assets/images/avatars/default-avatar.jpg'">
            </td>
            <td valign="top">
                <div style="font-size: 11px;">
                    <b style="font-size: 13px; color: #3B5998;">Evelyn Carolina Castro López</b><br>
                    <span style="color: #666; font-size: 10px;">Full Stack Student Developer</span>
                    <div style="margin-top: 8px; line-height: 1.6;">
                        <b>Carnet:</b> 241974<br>
                        <b>Email:</b> <a href="mailto:cas241974@uvg.edu.gt">cas241974@uvg.edu.gt</a><br>
                        <b>Role:</b> Backend logic, session management, database design, 
                        and interface recreation inspired by thefacebook (2004).
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

                      
<div>
    <h3 style="font-size: 13px; color: #3B5998; font-weight: bold; margin-bottom: 8px;">Technologies Used</h3>
    <ul style="margin-left: 20px; line-height: 1.8; font-size: 11px;">
        <li>PHP 7.4+ for server-side logic and session handling.</li>
        <li>MySQL for structured data storage (users, profiles, credentials).</li>
        <li>HTML5 for structure and layout.</li>
        <li>CSS3 replicating the aesthetic of thefacebook (2004).</li>
        <li>JavaScript for client-side validation of forms.</li>
        <li>Manual deployment on a free hosting service with public domain/subdomain.</li>
        <li>Design, color palette, and interface based on archived snapshots of the original site.</li>
    </ul>
</div>


    <!-- FOOTER -->
    <div class="footer">
        <a href="contact.php">about</a>
        <a href="#">faq</a>
        <a href="#">terms</a>
        <a href="#">privacy</a>
        <br>
        a Mark Zuckerberg production
        <br>
        Thefacebook © 2004 - University Project UVG
    </div>
</body>
</html>