<?php
// ============================================
// HEADER REUTILIZABLE - thefacebook 2004
// ============================================
// Este archivo contiene la barra superior azul
// que se usa en todas las páginas del sitio
// ============================================

// Verificar si el usuario está logueado para mostrar foto
$header_portrait = 'assets/images/avatars/mi-foto.jpg';

if (isset($_SESSION['user_id']) && isset($_SESSION['user_avatar'])) {
    // Si el usuario está logueado, usar su avatar
    $header_portrait = 'assets/images/avatars/' . htmlspecialchars($_SESSION['user_avatar']);
}
?>

<!-- NAVBAR AZUL - Barra superior con logo, foto y links -->
<div class="header">
    <div class="header-content">
        <!-- Foto de perfil a la izquierda -->
        <img src="<?php echo $header_portrait; ?>" 
             alt="" class="header-portrait"
             onerror="this.src='assets/images/avatars/default-avatar.jpg'">

        <!-- Logo y links alineados a la derecha -->
        <div class="header-right">
            <a href="index.php" class="header-logo">
                <span>[</span> thefacebook <span>]</span>
            <div class="header-links">
                <!-- Mostrar diferentes links según si está logueado o no -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Links para usuarios logueados -->
                    <a href="home.php">home</a>
                    <a href="#">search</a>
                    <a href="#">global</a>
                    <a href="#">social net</a>
                    <a href="#">invite</a>
                    <a href="#">faq</a>
                    <a href="logout.php">logout</a>
                <?php else: ?>
                    <!-- Links para usuarios no logueados -->
                    <a href="#" onclick="document.querySelector('input[type=email]').focus()" class=>Login</a>
                    <a href="register.php">register</a>
                    <a href="contact.php">about</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
