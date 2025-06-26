<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validate inputs
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Username or email already exists';
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $success = 'Registration successful. You can now login.';
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pucio matcha Register</title>
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
        .register-card {
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
        .register-logo {
            width: 54px;
            height: 54px;
            margin-bottom: 0.7rem;
            display: block;
        }
        .register-title {
            width: 100%;
            text-align: left;
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 0.2rem;
            letter-spacing: 0.5px;
        }
        .register-subtitle {
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
        input[type="text"], input[type="email"], input[type="password"] {
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
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border: 1.5px solid #b39ddb;
            outline: none;
            background: #fff;
        }
        .register-btn {
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
        .register-btn:hover {
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
        .message.success {
            background: #e6ffe6;
            color: #218838;
            border: 1px solid #b2f2b2;
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
            .register-card {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
            .register-title, .register-subtitle, .nav, .message {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <!-- Simple matcha bowl with sakura SVG icon -->
        <svg class="register-logo" viewBox="0 0 48 48" fill="none">
            <circle cx="24" cy="24" r="22" stroke="#8b6f36" stroke-width="2" fill="none"/>
            <path d="M14 28c0 4 4.5 8 10 8s10-4 10-8" stroke="#8b6f36" stroke-width="2" fill="none"/>
            <ellipse cx="24" cy="28" rx="10" ry="4" fill="#f7e7c5"/>
            <path d="M19 19c1-2 4-2 5 0m2-2c1-1 3-1 4 0" stroke="#8b6f36" stroke-width="1.2" fill="none"/>
            <circle cx="33" cy="17" r="2" fill="#fff" stroke="#8b6f36" stroke-width="1"/>
            <path d="M33 15.5v3M31.5 17h3" stroke="#8b6f36" stroke-width="1"/>
        </svg>
        <div class="register-title">Create account</div>
        <div class="register-subtitle">Sign up to join our Japanese Matcha Shop community.</div>
        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="message success">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>
        <form action="register.php" method="post" autocomplete="off" style="width:100%;">
            <div class="form-group">
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Password">
            </div>
            <button type="submit" class="register-btn">Register</button>
        </form>
        <div class="nav">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>