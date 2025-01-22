<?php
require_once 'cfg.php'; // Plik konfiguracyjny z połączeniem do bazy danych

// Sprawdzenie sesji
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funkcje koszyka
function addToCart($productId, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function updateCart($productId, $quantity) {
    if ($quantity > 0) {
        $_SESSION['cart'][$productId] = $quantity;
    } else {
        unset($_SESSION['cart'][$productId]);
    }
}

function showCart($pdo) {
    if (empty($_SESSION['cart'])) {
        echo '<p>Koszyk jest pusty.</p>';
        return;
    }

    echo '<table class="tabela-lista">';
    echo '<thead>
            <tr>
                <th>Produkt</th>
                <th>Obrazek</th>
                <th>Ilość</th>
                <th>Cena</th>
                <th>Łącznie</th>
                <th>Akcje</th>
            </tr>
          </thead>
          <tbody>';

    $total = 0;

    foreach ($_SESSION['cart'] as $productId => $quantity) {
        // Pobranie danych produktu, w tym obrazka
        $stmt = $pdo->prepare("SELECT tytul, cena_netto, vat, zdjecie FROM produkty WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if ($product) {
            // Obliczenie ceny brutto i całkowitej kwoty dla danego produktu
            $brutto = $product['cena_netto'] * (1 + $product['vat'] / 100);
            $subtotal = $brutto * $quantity;
            $total += $subtotal;

            // Wyświetlenie danych produktu w koszyku
            echo '<tr>
                    <td>' . htmlspecialchars($product['tytul']) . '</td>
                    <td><img src="' . htmlspecialchars($product['zdjecie']) . '" alt="Zdjęcie produktu" style="max-width: 100px; max-height: 100px;"></td>
                    <td>' . $quantity . '</td>
                    <td>' . number_format($brutto, 2) . ' zł</td>
                    <td>' . number_format($subtotal, 2) . ' zł</td>
                    <td>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="product_id" value="' . $productId . '">
                            <input type="number" name="quantity" value="' . $quantity . '" min="1">
                            <button type="submit" name="update_cart">Aktualizuj</button>
                            <button type="submit" name="remove_cart">Usuń</button>
                        </form>
                    </td>
                  </tr>';
        }
    }

    echo '</tbody></table>';
    echo '<p><strong>Łączna wartość: ' . number_format($total, 2) . ' zł</strong></p>';
    echo '<a href="checkout.php" class="przycisk-kup">Przejdź do realizacji zamówienia</a>';
}


// Obsługa akcji koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        addToCart($productId, $quantity);
        $_SESSION['message'] = 'Produkt został dodany do koszyka!';
        header('Location: index.php?idp=glowna');
        exit();
    }

    if (isset($_POST['update_cart'])) {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        updateCart($productId, $quantity);
        $_SESSION['message'] = 'Koszyk został zaktualizowany.';
        // Usuwamy header, aby odświeżyć zawartość bez pustej strony
    }

    if (isset($_POST['remove_cart'])) {
        $productId = (int)$_POST['product_id'];
        removeFromCart($productId);
        $_SESSION['message'] = 'Produkt został usunięty z koszyka.';
        // Usuwamy header, aby odświeżyć zawartość bez pustej strony
    }
}
?>

