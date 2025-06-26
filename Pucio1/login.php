<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'admin.php' : 'user.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $errors = [];

    if (empty($username) || empty($password)) {
        $errors[] = 'Both username and password are required';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: " . ($user['role'] == 'admin' ? 'admin.php' : 'user.php'));
            exit;
        } else {
            $errors[] = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pucio Matcha</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #fcfbf8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', 'Noto Sans JP', Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: "";
            position: fixed;
            inset: -40px; /* extend beyond viewport to hide blur border */
            z-index: 0;
            background: #fcfbf8 url('matcha1.jpg') center center/cover no-repeat;
            filter: blur(16px) brightness(0.85);
            opacity: 0.7;
            pointer-events: none;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px 0 rgba(60,72,88,0.07);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2.2rem 2.2rem 2.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.2rem;
            position: relative;
            z-index: 1;
        }
        .login-logo {
            width: 54px;
            height: 54px;
            margin-bottom: 0.7rem;
            display: block;
        }
        .login-title {
            width: 100%;
            text-align: left;
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 0.2rem;
            letter-spacing: 0.5px;
        }
        .login-subtitle {
            width: 100%;
            text-align: left;
            color: #6d6d6d;
            font-size: 1.05rem;
            margin-bottom: 1.2rem;
        }
        .form-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        label {
            font-size: 1rem;
            color: #4a5568;
            font-weight: 500;
            display: none;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 1.5px solid #d6c7f7;
            border-radius: 8px;
            font-size: 1.08rem;
            background: #fff;
            color: #222;
            transition: border 0.2s;
            margin-bottom: 0.7rem;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border: 1.5px solid #b39ddb;
            outline: none;
            background: #fff;
        }
        .login-btn {
            width: 100%;
            padding: 0.9rem;
            background: #8b6f36;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.15rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 0.2rem;
            box-shadow: 0 2px 8px rgba(60,72,88,0.04);
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: #6d552a;
        }
        .message {
            width: 100%;
            text-align: left;
            margin: 0.5rem 0 0.2rem 0;
            padding: 0.7rem 1rem;
            border-radius: 6px;
            font-size: 1rem;
        }
        .message.error {
            background: #fff3e6;
            color: #d90429;
            border: 1px solid #ffd6d6;
        }
        .nav {
            width: 100%;
            text-align: left;
            margin-top: 1.2rem;
        }
        .nav a {
            color: #8b6f36;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: text-decoration 0.2s;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            .login-card {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
            .login-title, .login-subtitle, .nav, .message {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Simple matcha bowl with sakura SVG icon -->
        <svg class="login-logo" viewBox="0 0 48 48" fill="none">
            <circle cx="24" cy="24" r="22" stroke="#8b6f36" stroke-width="2" fill="none"/>
            <path d="M14 28c0 4 4.5 8 10 8s10-4 10-8" stroke="#8b6f36" stroke-width="2" fill="none"/>
            <ellipse cx="24" cy="28" rx="10" ry="4" fill="#f7e7c5"/>
            <path d="M19 19c1-2 4-2 5 0m2-2c1-1 3-1 4 0" stroke="#8b6f36" stroke-width="1.2" fill="none"/>
            <circle cx="33" cy="17" r="2" fill="#fff" stroke="#8b6f36" stroke-width="1"/>
            <path d="M33 15.5v3M31.5 17h3" stroke="#8b6f36" stroke-width="1"/>
        </svg>
        <div class="login-title">Sign in</div>
        <div class="login-subtitle">Welcome to Pucio matcha:D please enter your username and password to access your account.</div>
        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post" autocomplete="off" style="width:100%;">
            <div class="form-group">
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Password">
            </div>
            <button type="submit" class="login-btn">Continue</button>
        </form>
        <div class="nav">
            <a href="register.php">Don't have an account? Register</a>
        </div>
    </div>
</body>
</html>