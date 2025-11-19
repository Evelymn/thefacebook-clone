<?php
// home.php - Profile Page thefacebook 2004
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook | <?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .sidebar-box { padding: 0; }
        .section-title { 
            background-color: #3B5998; 
            color: #fff; 
            padding: 6px 8px; 
            font-weight: bold; 
            font-size: 11px; 
            margin-bottom: 8px;
        }
        .section-title a { color: #fff; text-decoration: none; }
        .section-title a:hover { text-decoration: underline; }
        .sidebar-section { 
            margin-bottom: 8px; 
            font-size: 11px;
            padding: 4px 0;
        }
        .sidebar-section a { display: block; padding: 2px 0; }
        .profile-section {
            border: 1px solid #ccc;
            background: #f0f0f0;
            padding: 10px;
            margin-bottom: 12px;
        }
        .section-header {
            background: #3B5998;
            color: #fff;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 11px;
            margin: -10px -10px 10px -10px;
            overflow: hidden;
        }
        .profile-large-photo {
            width: 220px;
            height: auto;
            border: 1px solid #ccc;
            display: block;
            margin: 0 auto;
        }
        .info-label { 
            display: inline-block; 
            width: 120px; 
            font-weight: bold; 
            color: #666;
        }
        .info-value { color: #000; }
        .info-row { margin-bottom: 6px; font-size: 11px; }
    </style>
</head>
<body>
    <!-- INCLUIR HEADER REUTILIZABLE -->
    <?php include 'includes/header.php'; ?>

    <!-- Main container with sidebar and content -->
    <div class="container">
        <div class="outer-box">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <!-- LEFT SIDEBAR with navigation menu -->
                    <td class="sidebar">
                        <div class="login-panel">
                        <div class="sidebar-box">
                            <div class="section-title">
                                <a href="home.php"><?php echo htmlspecialchars($user['name']); ?>'s Profile</a>
                                <span style="float:right; font-size:10px;">(This is you)</span>
                            </div>

                            <!-- Quick Search Box -->
                            <div class="sidebar-section">
                                <input type="text" placeholder="Quick search" style="width:130px; padding:2px; border:1px solid #ccc; font-size:10px;">
                                <button style="padding:2px 6px; font-size:10px; margin-top:2px;">go</button>
                            </div>

                            <!-- Navigation Links -->
                            <div class="sidebar-section">
                                <a href="home.php">My Profile [edit]</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Groups</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Friends</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Messages</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">Inbox</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Pokes</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Mobile Info</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Account</a>
                            </div>
                            
                            <div class="sidebar-section">
                                <a href="#">My Privacy</a>
                            </div>
                        </div>
                        </div>
                    </td>

                    <!-- RIGHT CONTENT -->
                    <td class="content">
                        <!-- LEFT SIDE: PICTURE AND BUTTONS -->
                        <div style="float: left; width: 280px;">
                            <div class="profile-section">
                                <div class="section-header">
                                    Picture
                                    <a href="upload_photo.php" style="float:right; color:#fff; font-size:10px;">[edit]</a>
                                </div>
                                <img src="assets/images/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" 
                                     alt="Profile Photo" 
                                     class="profile-large-photo"
                                     onerror="this.src='assets/images/avatars/default-avatar.jpg'">
                                <div style="margin-top:10px; font-size:10px; text-align:center;">
                                    <a href="upload_photo.php">Visualize My Friends</a>
                                </div>
                                <div style="margin-top:6px; font-size:10px; text-align:center;">
                                    <a href="#">Edit My Profile</a>
                                </div>
                                <div style="margin-top:6px; font-size:10px; text-align:center;">
                                    <a href="#">My Account Preferences</a>
                                </div>
                                <div style="margin-top:6px; font-size:10px; text-align:center;">
                                    <a href="#">My Privacy Preferences</a>
                                </div>
                            </div>

                            <!-- CONNECTION SECTION -->
                            <div class="profile-section">
                                <div class="section-header">Connection</div>
                                <div style="background:#3B5998; color:#fff; padding:8px; font-size:11px; text-align:center;">
                                    This is you.
                                </div>
                            </div>

                            <!-- ACCESS SECTION -->
                            <div class="profile-section">
                                <div class="section-header">Access</div>
                                <div style="background:#f0f0f0; border:1px solid #ccc; padding:8px; margin:-10px -10px 0 -10px; font-size:10px;">
                                    <?php echo htmlspecialchars($user['name']); ?> is currently logged in from a non-residential location.
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT SIDE: INFORMATION BOXES -->
                        <div style="margin-left: 300px;">
                            <div class="content-box">
                                <div class="section-header">
                                    Information
                                    <a href="#" style="float:right; color:#fff; font-size:10px;">[edit]</a>
                                </div>

                                <!-- Account Info -->
                                <h3 style="font-size:12px; font-weight:bold; color:#3B5998; margin:12px 0 8px 0;">Account Info</h3>
                                
                                <div class="info-row">
                                    <span class="info-label">Name:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($user['name']); ?></span>
                                </div>
                                
                                <div class="info-row">
                                    <span class="info-label">Member Since:</span>
                                    <span class="info-value">
                                        <?php 
                                        $date = new DateTime($user['registration_date']);
                                        echo $date->format('F j, Y'); 
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="info-row">
                                    <span class="info-label">Last Update:</span>
                                    <span class="info-value">February 3, 2005</span>
                                </div>
                                
                                <div class="info-row">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value">
                                        <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </a>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Status:</span>
                                    <span class="info-value"><a href="#">Alumnus/Alumna</a></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Sex:</span>
                                    <span class="info-value">Male</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Year:</span>
                                    <span class="info-value">2004</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Concentration:</span>
                                    <span class="info-value">
                                        <a href="#">Computing Sciences</a>
                                    </span>
                                </div>

                                <!-- Basic Info -->
                                <h3 style="font-size:12px; font-weight:bold; color:#3B5998; margin:15px 0 8px 0; padding-top:10px; border-top:1px solid #eee;">Basic Info</h3>
                                
                                <div class="info-row">
                                    <span class="info-label">Phone:</span>
                                    <span class="info-value">502 2333</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">High School:</span>
                                    <span class="info-value">Gumnasio Hebreo '00</span>
                                </div>

                                <!-- Extended Info -->
                                <h3 style="font-size:12px; font-weight:bold; color:#3B5998; margin:15px 0 8px 0; padding-top:10px; border-top:1px solid #eee;">Extended Info
                                <a href="#" style="float:right; color:#3B5998; font-size:10px;">[edit]</a></h3>
                                
                                <div class="info-row">
                                    <span class="info-label">Screenname:</span>
                                    <span class="info-value">ScottP97</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Looking For:</span>
                                    <span class="info-value">Friendship</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Relationship Status:</span>
                                    <span class="info-value">Single</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Political Views:</span>
                                    <span class="info-value">Liberal</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Interests:</span>
                                    <span class="info-value">Drinking, Football, Basketball, Tennis, Saying you'll have that!</span>
                                </div>
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