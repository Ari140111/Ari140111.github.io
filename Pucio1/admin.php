<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Get all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            inset: -40px;
            z-index: 0;
            background: #fcfbf8 url('matcha1.jpg') center center/cover no-repeat;
            filter: blur(16px) brightness(0.85);
            opacity: 0.7;
            pointer-events: none;
        }
        .admin-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px 0 rgba(60,72,88,0.07);
            width: 100%;
            max-width: 700px;
            padding: 2.5rem 2.2rem 2.2rem 2.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }
        .admin-nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .admin-nav h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #7a6a46;
            margin: 0;
        }
        .admin-nav a {
            background: #7a6a46;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }
        .admin-nav a:hover {
            background: #5c4d2e;
        }
        .user-list {
            width: 100%;
        }
        .user-list h2 {
            color: #7a6a46;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #f8f6f1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 8px rgba(60,72,88,0.04);
        }
        th, td {
            padding: 0.85rem 1rem;
            text-align: left;
        }
        th {
            background: #e5e0d6;
            color: #7a6a46;
            font-weight: 600;
            font-size: 1rem;
        }
        tr:nth-child(even) td {
            background: #f3efe7;
        }
        tr:nth-child(odd) td {
            background: #f8f6f1;
        }
        @media (max-width: 800px) {
            .admin-card {
                max-width: 98vw;
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
            table, th, td {
                font-size: 0.98rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-card">
        <div class="admin-nav">
            <h1>Admin Dashboard</h1>
            <a href="logout.php">Logout</a>
        </div>
        <div class="user-list">
            <h2>All Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>