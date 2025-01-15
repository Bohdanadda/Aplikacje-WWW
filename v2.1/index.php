<?php
session_start(); // Start sesji na początku pliku

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
    default:
        $strona = ''; // Nieznana strona
        break;
}

// Obsługa żądania POST dla dodawania do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $cart->addToCart($product_id, $quantity, $price);
    header('Location: index.php?idp=koszyk'); // Przekierowanie do koszyka
    exit();
}

// Obsługa usuwania produktu z koszyka
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    $cart->removeFromCart($product_id);
    header('Location: index.php?idp=koszyk'); // Przekierowanie po usunięciu
    exit();
}

// Wyświetlenie komunikatów (jeśli istnieją)
if (!empty($_SESSION['message'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '</p>';
    unset($_SESSION['message']); // Usunięcie wiadomości po wyświetleniu
}

if (!empty($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . '</p>';
    unset($_SESSION['error']); // Usunięcie wiadomości po wyświetleniu
}

// Wczytanie pliku
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
    echo "<div class='lista-produktow'><h1>Lista produktów:</h1>";
    $produkty->PokazProdukty();
    echo "</div>";

    // Dodanie przykładowych kategorii, jeśli nie istnieją
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories WHERE nazwa = 'Elektronika'");
    if ($stmt->fetchColumn() == 0) {
        $kategorie->DodajKategorie('Elektronika');
        $kategorie->DodajKategorie('Telefony', 1);
        $kategorie->DodajKategorie('Laptopy', 1);
    }

    // Formularz dodawania produktów
    echo '<h2>Dodaj nowy produkt</h2>';
    echo '<form method="post" action="dodaj_produkt.php" class="formularz-dodawania-produktow">';
    echo 'Tytuł: <input type="text" name="tytul" required><br>';
    echo 'Opis: <textarea name="opis" required></textarea><br>';
    echo 'Cena netto: <input type="number" step="0.01" name="cena_netto" required><br>';
    echo 'VAT: <input type="number" name="vat" required><br>';
    echo 'Ilość: <input type="number" name="ilosc" required><br>';
    echo 'Status: <select name="status">
            <option value="dostępny">Dostępny</option>
            <option value="niedostępny">Niedostępny</option>
            <option value="na zamówienie">Na zamówienie</option>
        </select><br>';
    echo 'Kategoria: <input type="text" name="kategoria" required><br>';
    echo '<input type="submit" value="Dodaj Produkt">';
    echo '</form>';
}
?>
