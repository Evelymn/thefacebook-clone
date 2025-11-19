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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <a href="index.php" class="header-logo">thefacebook</a>
            <div class="header-links">
                <a href="login.php">login</a>
                <a href="register.php">register</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="register-container">
        <div class="register-box">
            <h1>Sign Up</h1>
            <p>It's free and anyone with a UVG university email address can join.</p>
            <br>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                    <a href="login.php"><b>Click here to login</b></a>
                </div>
            <?php endif; ?>
            
            <div class="register-form">
                <form method="POST" action="" onsubmit="return validateRegisterForm()">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td><b>Your Name:</b></td>
                            <td>
                                <input type="text" name="name" required 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><b>Your Email:</b></td>
                            <td>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <br>
                                <span class="small-text">You must use your UVG university email address</span>
                            </td>
                        </tr>
                        <tr>
                            <td><b>New Password:</b></td>
                            <td>
                                <input type="password" id="password" name="password" required minlength="6">
                                <br>
                                <span class="small-text">Minimum 6 characters</span>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Re-enter Password:</b></td>
                            <td>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><b>About Me:</b></td>
                            <td>
                                <textarea name="bio" rows="4"><?php echo isset($_POST['bio']) ? htmlspecialchars($_POST['bio']) : ''; ?></textarea>
                                <br>
                                <span class="small-text">(Optional) Tell us about yourself</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br>
                                By clicking Sign Up, you agree to our Terms and that you have read our Data Use Policy.
                                <br><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" value="Sign Up">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            
            <br>
            <p><b>Already have an account?</b> <a href="login.php">Login here</a></p>
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
    
    <script src="assets/js/validation.js"></script>
</body>
</html>
