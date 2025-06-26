<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] == 'admin') {
    header("Location: admin.php");
    exit;
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pucio matcha</title>
    <link rel="stylesheet" href="style.css">
    <style>
        html, body {
            height: 100%;
            margin: 55px;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8f6f1;
            font-family: 'Noto Sans JP', 'Segoe UI', Arial, sans-serif;
        }
        .logout-top {
            position: fixed;
            top: 32px;
            right: 48px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-direction: row-reverse; /* Puts logout button to the right */
        }
        .logout-btn {
            background: #6d552a;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.3rem 0.8rem;
            font-size: 0.92rem;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(60,72,88,0.06);
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
            height: 28px;
            line-height: 1.2;
        }
        .logout-btn:hover {
            background: #4e3e1e;
        }
        .user-meta {
            background: #f3efe7;
            border-radius: 6px;
            padding: 0.3rem 0.8rem;
            color: #7a6a46;
            font-size: 0.88rem;
            box-shadow: 0 1px 4px rgba(60,72,88,0.04);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 0 0 0;
            background: #f8f6f1;
        }
        .matcha-image {
            flex: 1 1 50%;
            max-width: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .matcha-image img {
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            box-shadow: 0 4px 32px 0 rgba(60,72,88,0.10);
            object-fit: cover;
        }
        .product-details {
            flex: 1 1 50%;
            max-width: 600px;
            padding: 0 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .store-label {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.92rem;
            color: #a89c7d;
            margin-bottom: 0.2rem;
        }
        .product-title {
            font-family: 'Georgia', serif;
            font-size: 3rem;
            color: #7a6a46;
            margin: 0.2rem 0 0.7rem 0;
            font-weight: 500;
        }
        .product-price {
            color: #7a6a46;
            font-size: 1.5rem;
            margin-bottom: 1.2rem;
            font-family: 'Georgia', serif;
        }
        .product-desc {
            color: #7a6a46;
            font-size: 1.15rem;
            margin-bottom: 2.2rem;
            line-height: 1.6;
            max-width: 480px;
        }
        .options-group {
            margin-bottom: 1.5rem;
        }
        .options-label {
            color: #7a6a46;
            font-size: 1.05rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .options {
            display: flex;
            gap: 1rem;
        }
        .option-btn {
            padding: 0.6rem 2.2rem;
            border-radius: 24px;
            border: 1.5px solid #b8ae99;
            background: #f3efe7;
            color: #7a6a46;
            font-size: 1.08rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .option-btn.selected, .option-btn:active {
            background: #7a6a46;
            color: #fff;
            border-color: #7a6a46;
        }
        .quantity-group {
            margin-bottom: 2.2rem;
        }
        .quantity-label {
            color: #7a6a46;
            font-size: 1.05rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            border: 1.5px solid #b8ae99;
            border-radius: 32px;
            width: fit-content;
            background: #f3efe7;
            overflow: hidden;
            height: 40px; /* Make the control shorter */
        }
        .quantity-btn {
            background: none;
            border: none;
            color: #7a6a46;
            font-size: 1.2rem; /* Smaller font */
            width: 35px;       /* Smaller button */
            height: 60px;
            cursor: pointer;
            font-weight: 700;
            transition: background 0.2s;
            padding: 0;
        }
        .quantity-value {
            min-width: 35px;   /* Smaller width */
            width: 30px;
            text-align: center;
            font-size: 1rem;   /* Smaller font */
            color: #7a6a46;
            font-weight: 600;
            background: none;
            border: none;
            height: 50px;
            padding: 0;
        }
        .add-cart-btn {
            width: 100%;
            margin-top: 1.2rem;
            background: #7a6a46;
            color: #fff;
            border: none;
            border-radius: 24px;
            padding: 0.9rem 0;
            font-size: 1.15rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(60,72,88,0.04);
            transition: background 0.2s;
            letter-spacing: 1px;
        }
        .add-cart-btn:hover {
            background: #5c4d2e;
        }
        footer {
            background: #7a6a46;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            font-size: 1rem;
            margin-top: 40px;
            width: 118.29%;
        }
        @media (max-width: 1100px) {
            .main-container {
                flex-direction: column;
                align-items: center;
                padding: 40px 0 0 0;
            }
            .matcha-image, .product-details {
                max-width: 90vw;
                padding: 0;
            }
            .product-details {
                margin-top: 32px;
            }
        }
        @media (max-width: 700px) {
            .main-container {
                padding: 20px 0 0 0;
            }
            .product-details {
                padding: 0 10px;
            }
            .logout-top {
                right: 10px; /* Changed from left to right */
                top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="logout-top">
        <a href="logout.php" class="logout-btn">Logout</a>
        <div class="user-meta">
            <span><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></span>
            <span><strong>Joined:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></span>
        </div>
    </div>
    <div class="main-container">
        <div class="matcha-image">
            <img src="matcha1.jpg" alt="Matcha Latte">
        </div>
        <div class="product-details">
            <div class="store-label">
                STORE: Pucio Matcha<br>
                LOCATION: Caloocan
            </div>
            <div class="product-title">Matcha Latte</div>
            <div class="product-price">₱220.00</div>
            <div class="product-desc">
                Made with premium first flush (ichibancha) tea from the renowned Uji region in Kyoto, Japan. Whisked to order; served hot or iced.
            </div>
            <form>
                <div class="options-group">
                    <div class="options-label">Kind</div>
                    <div class="options">
                        <button type="button" class="option-btn selected" id="kind-iced">Iced</button>
                        <button type="button" class="option-btn" id="kind-hot">Hot</button>
                    </div>
                </div>
                <div class="options-group">
                    <div class="options-label">Milk</div>
                    <div class="options">
                        <button type="button" class="option-btn selected" id="milk-dairy">Dairy</button>
                        <button type="button" class="option-btn" id="milk-oat">Oat</button>
                    </div>
                </div>
                <div class="quantity-group">
                    <div class="quantity-label">Quantity</div>
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn" id="qty-minus">−</button>
                        <input type="text" class="quantity-value" id="qty-value" value="1" readonly>
                        <button type="button" class="quantity-btn" id="qty-plus">+</button>
                    </div>
                </div>
                <button type="button" class="add-cart-btn">Add to Cart</button>
                <hr style="border: none; border-bottom: 2px solid #e5e0d6; margin: 2.5rem 0 0 0;">
            </form>
        </div>
    </div>
    <footer>
        &copy; 2025 Pucio matcha. All rights reserved.
    </footer>
    <script>
        // Option button toggle
        document.querySelectorAll('.options').forEach(group => {
            group.addEventListener('click', function(e) {
                if (e.target.classList.contains('option-btn')) {
                    group.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('selected'));
                    e.target.classList.add('selected');
                }
            });
        });
        // Quantity control
        const qtyValue = document.getElementById('qty-value');
        document.getElementById('qty-minus').onclick = function() {
            let val = parseInt(qtyValue.value, 10);
            if (val > 1) qtyValue.value = val - 1;
        };
        document.getElementById('qty-plus').onclick = function() {
            let val = parseInt(qtyValue.value, 10);
            qtyValue.value = val + 1;
        };
    </script>

</body>
</html>