<?php
require_once 'cfg.php'; // Łączenie z bazą danych
require_once 'cart.php'; // Funkcje koszyka, w tym `showCart`
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk</title>
    <link rel="stylesheet" href="style.css"> <!-- Ścieżka do Twojego pliku CSS -->
</head>
<body>
    <nav>
        <ul class="meni">
            <li><a href="index.php?idp=glowna">Strona główna</a></li>
            <li><a href="cart.php">Koszyk</a></li>
        </ul>
    </nav>

    <main>
        <h1>Twój koszyk</h1>
        <?php showCart($pdo); ?>
    </main>
</body>
</html>