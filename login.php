<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Check if the user is already logged in, if yes then redirect him to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Check for "remember me" cookie
if (isset($_COOKIE['remember_me'])) {
    $username_cookie = $_COOKIE['remember_me'];

    // Fetch user details from database using the cookie username
    $sql_cookie = "SELECT id, username, nim, nama_lengkap, foto FROM users WHERE username = ?";
    if($stmt_cookie = mysqli_prepare($conn, $sql_cookie)){
        mysqli_stmt_bind_param($stmt_cookie, "s", $param_username_cookie);
        $param_username_cookie = $username_cookie;
        if(mysqli_stmt_execute($stmt_cookie)){
            mysqli_stmt_store_result($stmt_cookie);
            if(mysqli_stmt_num_rows($stmt_cookie) == 1){
                mysqli_stmt_bind_result($stmt_cookie, $id, $username, $nim, $nama_lengkap, $foto);
                if(mysqli_stmt_fetch($stmt_cookie)){
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    $_SESSION["nama_lengkap"] = $nama_lengkap;
                    $_SESSION["foto"] = $foto;
                    header("location: index.php");
                    exit;
                }
            }
        }
        mysqli_stmt_close($stmt_cookie);
    }
}


$username = $password = "";
$login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username and password
    if(empty(trim($_POST["username"])) || empty(trim($_POST["password"]))){
        $login_err = "Username atau Password tidak boleh kosong.";
    } else {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]); // NIM as password
    }

    // Attempt to validate credentials
    if(empty($login_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, nim, nama_lengkap, foto FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username_db, $nim_db, $nama_lengkap, $foto);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password === $nim_db){ // Verify NIM as password
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username_db;
                            $_SESSION["nama_lengkap"] = $nama_lengkap;
                            $_SESSION["foto"] = $foto;

                            // Set "Remember Me" cookie
                            if (isset($_POST['remember_me'])) {
                                setcookie('remember_me', $username_db, time() + (86400 * 30), "/"); // 30 days
                            } else {
                                // Clear cookie if not checked
                                if (isset($_COOKIE['remember_me'])) {
                                    setcookie('remember_me', '', time() - 3600, "/");
                                }
                            }

                            // Redirect user to index page
                            header("location: index.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Username atau Password salah.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Username atau Password salah.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}

// Get student data for display in the top-left header
$student_data_header = null;
$sql_student_header = "SELECT username, nim, nama_lengkap, foto FROM users LIMIT 1";
$result_student_header = mysqli_query($conn, $sql_student_header);
if ($result_student_header && mysqli_num_rows($result_student_header) > 0) {
    $student_data_header = mysqli_fetch_assoc($result_student_header);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - minglewithk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Link to your main style.css to inherit background and general body styles -->
    <link rel="stylesheet" href="style.css">
    <style>
        /* Override/Add styles specific for login page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* background-color and other body styles are from style.css */
        }

        /* NEW: Top-left header with profile info */
        .top-left-header {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 110;
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            border-radius: 50px; /* Membuat bentuk pil */
            padding: 10px 20px 10px 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
            color: #083352;
            transition: all 0.3s ease;
        }
        .top-left-header:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .header-profile-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid #B4CDDF;
            /* Filter untuk memastikan gambar tetap terlihat bagus */
            filter: brightness(0.95);
        }
        .header-info {
            display: flex;
            flex-direction: column;
            font-size: 0.9rem;
            line-height: 1.2;
        }
        .header-info span:first-child {
            font-weight: 600;
        }
        .header-info span:last-child {
            font-weight: 400;
            color: #555; /* Warna teks yang sedikit lebih redup untuk NIM */
        }

        /* Login Container (white frame) */
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid #083352;
            border-radius: 25px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 40px rgba(8, 51, 82, 0.1), 0 1px 3px rgba(8, 51, 82, 0.05);
            text-align: center;
            animation: slideUpFade 1s ease-out;
            margin-top: 50px; /* Tambahan margin agar tidak terlalu dekat dengan header baru */
        }
        .login-container h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #083352;
            margin-bottom: 1.5rem;
        }
        /* Hide the old login-profile section as it's now in top-left header */
        .login-profile {
            display: none;
        }
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #083352;
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #083352;
            box-shadow: 0 0 0 3px rgba(8, 51, 82, 0.2);
        }
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            justify-content: flex-start;
        }
        .remember-me input[type="checkbox"] {
            margin-right: 0.5rem;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .remember-me label {
            font-weight: 400;
            color: #2c3e50;
            margin-bottom: 0;
            cursor: pointer;
        }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #B4CDDF 0%, #7BB3D9 100%);
            color: #FFFFFF;
            border: none;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(180, 205, 223, 0.4);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #7BB3D9 0%, #B4CDDF 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(180, 205, 223, 0.6);
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
        }
        /* Background video and overlay styles from main style.css, ensure they are also applied */
        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100vw;
            min-height: 100vh;
            width: auto;
            height: auto;
            z-index: -2;
            object-fit: cover;
            opacity: 0;
            will-change: transform;
            transform: translateZ(0);
            animation: fadeInVideo 2s ease-in-out forwards;
        }
        @keyframes fadeInVideo {
            from { opacity: 0; }
            to { opacity: 0.5; }
        }
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(180, 205, 223, 0.2);
            z-index: -1;
            opacity: 0;
            animation: fadeInBg 2s ease forwards;
        }
        @keyframes fadeInBg {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        /* Keyframe animations for main content (used by login-container) */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive adjustments for top-left header */
        @media (max-width: 768px) {
            .top-left-header {
                top: 10px;
                left: 10px;
                padding: 8px 15px 8px 8px;
                font-size: 0.8rem;
            }
            .header-profile-photo {
                width: 30px;
                height: 30px;
                margin-right: 8px;
            }
            .header-info {
                font-size: 0.8rem;
            }
            .login-container {
                max-width: 90%;
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video id="bg-video" autoplay muted loop playsinline preload="auto">
        <source src="video/star.mp4" type="video/mp4">
    </video>

    <!-- NEW: Top-left header with profile info -->
    <div class="top-left-header">
        <?php if ($student_data_header): ?>
            <img src="img/pfp.jpg" alt="Profile Photo" class="header-profile-photo">
            <div class="header-info">
                <span><?php echo htmlspecialchars($student_data_header['nama_lengkap']); ?></span>
                <span>(<?php echo htmlspecialchars($student_data_header['nim']); ?>)</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="login-container">
        <h2>Welcome</h2>
        <p>Silakan masukkan Username dan Password Anda.</p>

        <?php
        if(!empty($login_err)){
            echo '<div class="error-message">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="remember-me">
                <input type="checkbox" name="remember_me" id="remember_me" value="1">
                <label for="remember_me">Remember Me</label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-login" value="Login">
            </div>
        </form>
    </div>
</body>
</html>