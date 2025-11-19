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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $bio = sanitizeInput($_POST['bio'] ?? '');
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!isUniversityEmail($email)) {
        $error = "You must use a UVG university email address (@uvg.edu.gt or @est.uvg.edu.gt).";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, bio) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password, $bio);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
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
    <!-- INCLUIR HEADER REUTILIZABLE -->
    <?php include 'includes/header.php'; ?>

    <!-- Contenedor principal con sidebar y contenido -->
    <div class="container">
        <div class="outer-box">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <!-- SIDEBAR IZQUIERDO con opciones de login -->
                    <td class="sidebar">
                        <div class="login-panel">
                            <div class="login-sidebar-box">
                                <div class="login-section-title">
                                    <a href="#">main</a>
                                </div>

                                <!-- Link para ir a login -->
                                <div class="login-sidebar-section">
                                    <a href="login.php">[ login ]</a>
                                </div>
                                
                                <!-- Link para ir a register (página actual) -->
                                <div class="login-sidebar-section">
                                    <a href="register.php">[ register ]</a>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- CONTENIDO DERECHO con formulario de registro -->
                    <td class="content">
                        <div class="content-box">
                            <!-- Encabezado con título Registration -->
                            <div class="login-content-header">Registration</div>

                            <!-- Texto introductorio -->
                            <div style="margin: 15px 0; font-size: 11px; line-height: 1.6;">
                                <p>To register for thefacebook.com, just fill in the four fields below. You will have a chance to enter additional information and submit a picture once you have registered.</p>
                            </div>

                            <!-- Mostrar errores si existen -->
                            <?php if ($error): ?>
                                <div class="error-message">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Mostrar mensaje de éxito si el registro fue exitoso -->
                            <?php if ($success): ?>
                                <div class="success-message">
                                    <?php echo $success; ?>
                                    <a href="login.php"><b>Click here to login</b></a>
                                </div>
                            <?php endif; ?>

                            <!-- Formulario de registro -->
                            <form method="POST" action="" style="margin: 20px 0;">
                                <!-- Campo: Nombre -->
                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Name:</label>
                                    <input type="text" name="name" required 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                           style="width: 200px; padding: 3px; border: 1px solid #999;">
                                </div>

                                <!-- Campo: Estado (Dropdown) -->
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

                                <!-- Campo: Email (con nota de que debe ser de la universidad) -->
                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Email:</label>
                                    <input type="email" id="email" name="email" required 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                           style="width: 200px; padding: 3px; border: 1px solid #999;">
                                    <span style="font-size: 9px; color: #999;">(school)</span>
                                </div>

                                <!-- Campo: Contraseña con nota sobre seguridad -->
                                <div style="margin-bottom: 12px;">
                                    <label style="display: inline-block; width: 100px; font-weight: bold;">Password*:</label>
                                    <input type="password" id="password" name="password" required minlength="6"
                                           style="width: 200px; padding: 3px; border: 1px solid #999;">
                                    <span style="font-size: 9px; color: #999;">(choose)</span>
                                    <div style="margin-top: 4px; font-size: 10px; color: #999; margin-left: 100px;">
                                        * You can choose any password. It should not be your school password.
                                    </div>
                                </div>

                                <!-- Campo: Confirmar contraseña -->
                                <div style="margin-bottom: 12px;">
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                           style="width: 200px; padding: 3px; border: 1px solid #999; margin-left: 100px;">
                                </div>

                                <!-- Checkbox: Aceptar términos de uso -->
                                <div style="margin-bottom: 15px; margin-left: 100px;">
                                    <input type="checkbox" name="agree_terms" required>
                                    I have read and understood the <a href="#">Terms of Use</a>, and I agree to them.
                                </div>

                                <!-- Botón de registro -->
                                <div style="text-align: center; margin: 20px 0;">
                                    <input type="submit" value="Register Now!" class="btn btn-primary">
                                </div>
                            </form>

                            <!-- Link para login si ya tiene cuenta -->
                            <div style="font-size: 11px; text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #ccc;">
                                Already have an account? <a href="login.php"><b>Login here</b></a>
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
    
    <script src="assets/js/validation.js"></script>
</body>
</html>
