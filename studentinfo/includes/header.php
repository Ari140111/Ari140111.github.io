<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: url('hehea.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            filter: blur(8px) brightness(0.9);
            -webkit-filter: blur(8px) brightness(0.9);
            z-index: -1;
        }
        
        .header-nav {
            padding: 25px 0;
            background-color: transparent;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .nav-btn {
            background-color: rgba(255, 255, 255, 0.85);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        
        .nav-btn:hover {
            background-color: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: #0069d9;
        }
        
        .nav-btn.active {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
        }
        .header-content {
            position: relative;
        }
        .logout-container {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <header class="header-nav">
        <div class="container">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="header-content">
                    <div class="nav-buttons">
                        <a href="index.php" class="nav-btn <?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
                        <a href="add.php" class="nav-btn <?= $current_page == 'add.php' ? 'active' : '' ?>">Add Student</a>
                        <a href="view.php" class="nav-btn <?= in_array($current_page, ['view.php', 'edit.php']) ? 'active' : '' ?>">View Students</a>
                    </div>
                    <div class="logout-container">
                        <a href="logout.php" class="nav-btn">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>