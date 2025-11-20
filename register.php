<?php
// register.php - thefacebook 2004 style
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}
require_once 'config/database.php';

$error = '';
$success = '';
$login_error = '';

// ---------------------------------------------------------
// 1. PROCESAR LOGIN (Sidebar)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_submit'])) {
    $email = sanitizeInput($_POST['login_email'] ?? '');
    $password = $_POST['login_password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
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

// ---------------------------------------------------------
// 2. PROCESAR REGISTRO (Formulario Principal)
// ---------------------------------------------------------
// Detectamos si es registro verficando que NO sea login y que venga el campo 'name'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['login_submit']) && isset($_POST['name'])) {
    
    $name = sanitizeInput($_POST['name']);
    $status = sanitizeInput($_POST['status']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $agree_terms = isset($_POST['agree_terms']);

    // Validaciones básicas
    if ($password !== $confirm_password) {
        $login_error = "Passwords do not match."; // Usamos login_error para que salga en rojo arriba
    } elseif (strlen($password) < 6) {
        $login_error = "Password must be at least 6 characters.";
    } elseif (!$agree_terms) {
        $login_error = "You must agree to the Terms of Use.";
    } else {
        // Verificar si el email ya existe
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $login_error = "That email is already registered.";
        } else {
            // INSERTAR NUEVO USUARIO
            // Nota: En producción deberías usar password_hash($password, PASSWORD_DEFAULT)
            // Pero para mantener el estilo 2004 simple, lo dejaremos texto plano o como lo tengas configurado
          $stmt_insert = $conn->prepare("INSERT INTO users (name, status, email, password, registration_date) VALUES (?, ?, ?, ?, NOW())");
            $stmt_insert->bind_param("ssss", $name, $status, $email, $password);

            if ($stmt_insert->execute()) {
                $success = "Registration successful! You can now login.";
                // Limpiar formulario
                $_POST = array();
            } else {
                $login_error = "Error creating account. Please try again.";
            }
            $stmt_insert->close();
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
    <title>thefacebook | sign up</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2.0">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="outer-box">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="sidebar">
                        <div class="login-panel">
                            <form method="POST" action="">
                                <input type="hidden" name="login_submit" value="1">
                                <table cellpadding="2" cellspacing="0">
                                    <tr class="input-row">
                                        <td align="right"><b>Email:</b></td>
                                    </tr>
                                    <tr class="input-row">
                                        <td>
<input type="email" id="sidebar_email" name="login_email" required class="input-field" value="<?php echo isset($_POST['login_email']) ? htmlspecialchars($_POST['login_email']) : ''; ?>">                                        </td>
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
                                <?php if ($login_error && isset($_POST['login_submit'])): ?>
                                    <div class="error-message">
                                        <?php echo $login_error; ?>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </td>

                    <td class="content">
                      <div style="background-color: #3B5998; color: #ffffff; padding: 6px 10px; 
                                    font-weight: bold; margin-bottom: 12px; font-size: 11px;">
                            Registation
                        </div>
                            <div style="margin: 15px 0; font-size: 11px; line-height: 1.6;">
                                <p>To register for thefacebook.com, just fill in the four fields below. You will have a chance to enter additional information and submit a picture once you have registered.</p>
                            </div>

                            <?php if ($login_error && !isset($_POST['login_submit'])): ?>
                                <div class="error-message" style="color: red; margin-bottom: 10px;">
                                    <?php echo $login_error; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="success-message" style="background-color: #f0f8ff; border: 1px solid #d9edf7; color: #31708f; padding: 10px; margin-bottom: 15px;">
                                    <?php echo $success; ?>
<a href="#" onclick="document.getElementById('sidebar_email').focus(); return false;" style="font-weight: bold;">Click here to login</a>                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" style="margin: 20px 0;">
                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Name:</label>
                                    <input type="text" name="name" required 
                                           value="<?php echo isset($_POST['name']) && empty($success) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                           style="width: 200px; padding: 3px; border: 1px solid #999;">
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Status:</label>
                                    <select name="status" style="width: 200px; padding: 3px; border: 1px solid #999;">
                                        <option value="Student (Full-Time)">Student (Full-Time)</option>
                                        <option value="Student (Part-Time)">Student (Part-Time)</option>
                                        <option value="Alumni">Alumni</option>
                                        <option value="Faculty">Faculty</option>
                                        <option value="Staff">Staff</option>
                                    </select>
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Email:</label>
                                    <input type="email" id="email" name="email" required 
                                           value="<?php echo isset($_POST['email']) && empty($success) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                           style="width: 200px; padding: 3px; border: 1px solid #999;">
                                    <span style="font-size: 9px; color: #999;">(school)</span>
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Password*:</label>
                                    <input type="password" id="password" name="password" required minlength="6"
                                           style="width: 200px; padding: 3px; border: 1px solid #999;">
                                    <span style="font-size: 9px; color: #999;">(choose)</span>
                                    <div style="margin-top: 4px; font-size: 10px; color: #999; margin-left: 100px;">
                                        * You can choose any password. It should not be your school password.
                                    </div>
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                           style="width: 200px; padding: 3px; border: 1px solid #999; margin-left: 100px;">
                                </div>

                                <div style="margin-bottom: 15px; margin-left: 100px;">
                                    <input type="checkbox" name="agree_terms" required>
                                    I have read and understood the <a href="#">Terms of Use</a>, and I agree to them.
                                </div>

                                <div style="text-align: center; margin: 20px 0;">
                                    <input type="submit" value="Register Now!" class="btn btn-primary">
                                </div>
                            </form>

                           <div style="font-size: 11px; text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #ccc;">
    Already have an account? <a href="#" onclick="document.getElementById('sidebar_email').focus(); return false;"><b>Login here</b></a>
</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <a href="contact.php">about</a>
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
    
    <script src="assets/js/validation.js"></script>
</body>
</html>