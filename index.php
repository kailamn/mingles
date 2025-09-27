<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>minglewithk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.body.classList.add("loaded");
});
</script>

<body>
    <!-- Background Video -->
    <video id="bg-video" autoplay muted loop playsinline preload="auto">
        <source src="video/star.mp4" type="video/mp4">
    </video>


    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo" onclick="showPage('home')">minglewithk</div>
        <ul class="nav-links">
            <li><a href="#" onclick="showPage('home')">Home</a></li>
            <li><a href="#" onclick="showPage('about')">About</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- Tambahkan link logout -->
        </ul>
    </nav>

    <!-- Homepage -->
    <div id="home" class="page active">
        <div class="container">
            <div class="main-content">
                <div class="text-content">
                    <h1>Hi, I'm Kaila</h1>
                    <p>Absorbed in information systems analysis, digital technology adoption, and user experience, with strong analytical skills</p>
                    <div class="buttons">
                        <a href="#" class="btn btn-primary">View My Work</a>
                        <a href="#" class="btn btn-secondary">Get In Touch</a>
                    </div>
                </div>
                <div class="profile-img">
                    <img src="img/pfp.jpg" alt="Profile" class="profile-photo">
                </div>
            </div>
        </div>
    </div>

    <!-- About Page -->
    <div id="about" class="page">
        <div class="container">
            <div class="main-content">
                <div class="about-content">
                    <h1>Overview</h1>
                    <p class="subtitle">A student at Universitas Negeri Semarang</p>
                    <div class="info-item">
                        <strong>Full name:</strong> Kaila Maritza Nabilah
                    </div>
                    <div class="info-item">
                        <strong>Study Program:</strong> Information Systems
                    </div>
                    <div class="info-item">
                        <strong>Student ID:</strong> 2304140067
                    </div>
                </div>
                <div class="campus-img">
                    <img src="img/logo-unnes.jpg" alt="UNNES Logo" class="campus-logo">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with accessibility improvements -->
     <footer class="footer">
        <div class="footer-text">
            <h3>Keep In Touch</h3>
            <p>Follow me more on:</p>
        </div>
        <div class="social-links">
            <a href="https://www.instagram.com/kaillamn/" class="social-link" target="_blank" rel="noopener">
                <img src="img/ig.png" alt="Instagram" width="28" height="28">
            </a>
            <a href="https://www.linkedin.com/in/kailamn/" class="social-link" target="_blank" rel="noopener">
                <img src="img/linkedin.png" alt="LinkedIn" width="32" height="32">
            </a>
            <a href="https://github.com/kailamn" class="social-link" target="_blank" rel="noopener">
                <img src="img/github.png" alt="GitHub" width="28" height="28">
            </a>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>