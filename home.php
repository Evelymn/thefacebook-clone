<?php
// home.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'config/database.php';

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: logout.php");
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook - <?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="home.php" class="navbar-brand">thefacebook</a>
        
        <div class="navbar-menu">
            <a href="home.php">Mi Perfil</a>
            <a href="contact.php">Creadores</a>
            
            <div class="navbar-user">
                <img src="assets/images/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" 
                     alt="Avatar" 
                     class="navbar-avatar"
                     onerror="this.src='assets/images/avatars/default-avatar.jpg'">
                <span><?php echo htmlspecialchars($user['name']); ?></span>
                <a href="logout.php" style="margin-left: 10px;">[salir]</a>
            </div>
        </div>
    </nav>
    
    <div class="main-container">
        <div class="profile-header">
            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        </div>
        
        <div class="profile-content">
            <div class="profile-sidebar">
                <img src="assets/images/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" 
                     alt="Foto de perfil" 
                     class="profile-picture"
                     onerror="this.src='assets/images/avatars/default-avatar.jpg'">
            </div>
            
            <div class="profile-info">
                <div class="info-section">
                    <h3>Información Personal</h3>
                    
                    <div class="info-row">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Correo Universitario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Miembro desde:</span>
                        <span class="info-value">
                            <?php 
                            $date = new DateTime($user['registration_date']);
                            echo $date->format('d/m/Y'); 
                            ?>
                        </span>
                    </div>
                </div>
                
                <?php if (!empty($user['bio'])): ?>
                <div class="info-section">
                    <h3>Acerca de mí</h3>
                    <p style="font-size: 11px; line-height: 1.6; color: #333;">
                        <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <div class="info-section">
                    <h3>Redes</h3>
                    <p style="font-size: 11px; color: #666;">
                        Red Universitaria UVG
                    </p>
                </div>
                
                <div class="info-section" style="border-bottom: none;">
                    <h3>Estado de Cuenta</h3>
                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value" style="color: #5ba85b; font-weight: bold;">Activo</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tipo:</span>
                        <span class="info-value">Estudiante Universitario</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 20px; color: #666; font-size: 10px;">
        <p>thefacebook © 2024 - Proyecto Universitario UVG</p>
        <p><a href="contact.php" style="color: #3b5998;">Acerca de los Creadores</a></p>
    </footer>
</body>
</html>
\`\`\`
