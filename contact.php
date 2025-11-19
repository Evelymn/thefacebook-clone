<?php
// contact.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'config/database.php';

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT name, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook - Creadores del Proyecto</title>
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
    </div>
    
    <div class="main-container">
        <div class="profile-header">
            <h1>Creadores del Proyecto</h1>
            <p style="font-size: 11px; margin-top: 5px;">Proyecto de Programación Web - UVG 2024</p>
        </div>
        
        <div style="padding: 20px;">
            <div class="info-section">
                <h3>Acerca de este Proyecto</h3>
                <p style="font-size: 11px; line-height: 1.6; margin-bottom: 15px;">
                    Este es un clon de <strong>thefacebook</strong>, la versión original de Facebook lanzada en 2004. 
                    El proyecto fue desarrollado como parte de un curso universitario en la Universidad del Valle de Guatemala (UVG).
                </p>
                <p style="font-size: 11px; line-height: 1.6; margin-bottom: 15px;">
                    Se utilizaron tecnologías web fundamentales como PHP, MySQL, HTML, CSS y JavaScript para recrear 
                    la experiencia de la red social universitaria original, manteniendo su diseño minimalista y funcional.
                </p>
            </div>
            
            <div class="info-section">
                <h3>Equipo de Desarrollo</h3>
                <p style="font-size: 11px; color: #666; margin-bottom: 15px;">
                    Conoce al equipo detrás de este proyecto:
                </p>
            </div>
        </div>
        
        <div class="creators-grid">
            <div class="creator-card">
                <img src="assets/images/creators/creator1.jpg" 
                     alt="Creador 1" 
                     class="creator-photo"
                     onerror="this.src='assets/images/avatars/default-avatar.jpg'">
                <h3>Tu Nombre Aquí</h3>
                <p><strong>Rol:</strong> Full Stack Developer</p>
                <p><strong>Carnet:</strong> 21XXXXX</p>
                <p><strong>Email:</strong> tunombre@uvg.edu.gt</p>
                <p style="margin-top: 10px; font-style: italic;">
                    Responsable del desarrollo backend y base de datos.
                </p>
            </div>
            
            <div class="creator-card">
                <img src="assets/images/creators/creator2.jpg" 
                     alt="Creador 2" 
                     class="creator-photo"
                     onerror="this.src='assets/images/avatars/default-avatar.jpg'">
                <h3>Compañero/a de Equipo</h3>
                <p><strong>Rol:</strong> Frontend Developer</p>
                <p><strong>Carnet:</strong> 21XXXXX</p>
                <p><strong>Email:</strong> companero@uvg.edu.gt</p>
                <p style="margin-top: 10px; font-style: italic;">
                    Responsable del diseño y experiencia de usuario.
                </p>
            </div>
        </div>
        
        <div style="padding: 20px;">
            <div class="info-section" style="border-bottom: none;">
                <h3>Tecnologías Utilizadas</h3>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li style="margin-bottom: 5px;">PHP 7.4+ para el backend</li>
                    <li style="margin-bottom: 5px;">MySQL para la base de datos</li>
                    <li style="margin-bottom: 5px;">HTML5 y CSS3 para la estructura y diseño</li>
                    <li style="margin-bottom: 5px;">JavaScript vanilla para validaciones</li>
                    <li style="margin-bottom: 5px;">Diseño responsive para dispositivos móviles</li>
                </ul>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 20px; color: #666; font-size: 10px;">
        <p>thefacebook © 2024 - Proyecto Universitario UVG</p>
        <p>Inspirado en la versión original de Facebook (2004)</p>
    </footer>
</body>
</html>
