<?php

include('header.php'); // Pasek zakładek i style
include('cfg.php'); // Załączenie pliku konfiguracyjnego, który zawiera $pdo
include('cart.php'); // Dołączenie logiki koszyka

$idp = isset($_GET['idp']) ? htmlspecialchars($_GET['idp'], ENT_QUOTES, 'UTF-8') : 'glowna'; // Walidacja

// Ustalanie ścieżki do pliku na podstawie wartości $idp
switch ($idp) {
    case 'glowna':
        $strona = 'html/glowna.html';
        break;
    case 'inne':
        $strona = 'html/inne.html';
        break;
    case 'menu':
        $strona = 'html/menu.html';
        break;
    case 'o_mnie':
        $strona = 'html/o_mnie.html';
        break;
    case 'filmy':
        $strona = 'html/filmy.html';
        break;
    case 'kontakt':
        $strona = 'contact.php'; // Strona kontaktowa
        break;
    case 'koszyk':
        $strona = 'show_koszyk.php'; // Koszyk obsługiwany poniżej
        break;
    default:
        $strona = ''; // Nieznana strona
        break;
}

// Obsługa żądania POST dla dodawania do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    // Dodaj produkt do koszyka
    addToCart($product_id, $quantity);

    $_SESSION['message'] = 'Produkt został dodany do koszyka!';
    header('Location: index.php?idp=glowna'); // Pozostań na stronie głównej
    exit();
}

// Wyświetlenie komunikatów (jeśli istnieją)
if (!empty($_SESSION['message'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '</p>';
    unset($_SESSION['message']); // Usunięcie wiadomości po wyświetleniu
}

if (!empty($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . '</p>';
    unset($_SESSION['error']); // Usunięcie błędu po wyświetleniu
}

// Obsługa koszyka
if ($idp === 'koszyk') {
    if (empty($_SESSION['cart'])) {
    } else {
        echo '<table class="tabela-lista">';
        echo '<thead><tr><th>Produkt</th><th>Ilość</th><th>Akcje</th></tr></thead><tbody>';
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $pdo->prepare("SELECT tytul FROM produkty WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();

            if ($product) {
                echo '<tr>
                        <td>' . htmlspecialchars($product['tytul']) . '</td>
                        <td>' . $quantity . '</td>
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
    }
    exit(); // Nie ładujemy więcej, jeśli jesteśmy w koszyku
}

// Wczytanie odpowiedniego pliku
if (!empty($strona) && file_exists($strona)) {
    if ($idp === 'kontakt') {
        include('contact.php');
        PokazKontakt(); // Funkcja do wyświetlania kontaktu
    } else {
        include($strona);
    }
} else {
    echo "Strona nie istnieje!";
}

// Logika strony głównej
if ($idp === 'glowna') {
    require_once 'kategorie.php';
    $kategorie = new Kategorie($pdo);

    $produkty = new Produkty($pdo);
    echo "<div class='lista-produktow-naglowek'><h1>Lista produktów:</h1>";
    $produkty->PokazProdukty();
    echo "</div>";
    // Formularz dodawania produktów
    echo '<h2>Dodaj nowy produkt</h2>';
    echo '<form method="post" action="dodaj_produkt.php" enctype="multipart/form-data" class="formularz-dodawania-produktow">';
    echo 'Tytuł: <input type="text" name="tytul" required><br>';
    echo 'Opis: <textarea name="opis" required></textarea><br>';
    echo 'Cena netto: <input type="number" step="0.01" name="cena_netto" required><br>';
    echo 'VAT: <input type="number" name="vat" required><br>';
    echo 'Ilość na dostępnych: <input type="number" name="ilosc" min="0" required><br>';
    echo 'Status: <select name="status">
            <option value="dostępny">Dostępny</option>
            <option value="niedostępny">Niedostępny</option>
            <option value="na zamówienie">Na zamówienie</option>
        </select><br>';
    echo 'Kategoria: <input type="text" name="kategoria" required><br>';
    echo 'Zdjęcie: <input type="file" name="zdjecie" accept="image/*" required><br>';
    echo '<input type="submit" value="Dodaj Produkt">';
    echo '</form>';

}

?>
