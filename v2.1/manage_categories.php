<?php

require_once 'cfg.php';  // Dołączenie konfiguracji bazy danych
require_once 'CategoryManager.php';  // Klasa zarządzająca kategoriami

$categoryManager = new CategoryManager($pdo);
// Tutaj możesz teraz używać $categoryManager do wywoływania metod na kategoriach
 // Zakładam, że CategoryManager jest już zaimplementowany i zawiera połączenie PDO
$categoryManager = new CategoryManager($pdo);  // Tworzenie instancji klasy

// Obsługa formularza dodawania kategorii
if (isset($_POST['add'])) {
    $nazwa = $_POST['nazwa'];
    $matka = $_POST['matka'] ?? 0;  // Jeśli nie jest wybrana żadna kategoria nadrzędna, wartość domyślna to 0
    $categoryManager->dodajKategorie($nazwa, $matka);
}

// Obsługa formularza usuwania kategorii
if (isset($_POST['delete'])) {
    $id = $_POST['category_id'];
    $categoryManager->usunKategorie($id);
}

// Obsługa formularza edycji kategorii
if (isset($_POST['edit'])) {
    $id = $_POST['category_id'];
    $nowaNazwa = $_POST['new_name'];
    $categoryManager->edytujKategorie($id, $nowaNazwa);
}

// Formularze HTML dla dodawania, edycji i usuwania kategorii
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Kategoriami</title>
    <link rel="stylesheet" href="style.css"> <!-- Link do pliku CSS -->
</head>
<body>
    <!-- Pasek nawigacyjny -->
    <nav>
    <ul class="meni">
            <li><a href="index.php?idp=glowna">Główna</a></li>
            <li><a href="index.php?idp=inne">Inne</a></li>
            <li><a href="index.php?idp=menu">Menu</a></li>
            <li><a href="index.php?idp=o_mnie">O mnie</a></li>
            <li><a href="index.php?idp=filmy">Filmy</a></li>
            <li><a href="index.php?idp=kontakt">Kontakt</a></li>
            <li><a href="index.php?idp=koszyk">Koszyk</a></li>
            <li><a href="manage_categories.php">Zarządzaj kategoriami</a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <h1 class="tytle">Zarządzanie Kategoriami</h1>
    </div>

    <section id="dodaj" class="formularz-dodawania-produktow">
        <h2>Dodaj kategorię</h2>
        <form method="post">
            <label for="nazwa">Nazwa kategorii:</label>
            <input type="text" id="nazwa" name="nazwa" required>

            <label for="matka">Kategoria nadrzędna (ID):</label>
            <input type="number" id="matka" name="matka">

            <input type="submit" name="add" value="Dodaj Kategorię">
        </form>
    </section>

    <section id="usun" class="formularz-dodawania-produktow">
        <h2>Usuń kategorię</h2>
        <form method="post">
            <label for="category_id">ID kategorii:</label>
            <input type="number" id="category_id" name="category_id" required>

            <input type="submit" name="delete" value="Usuń Kategorię">
        </form>
    </section>

    <section id="edytuj" class="formularz-dodawania-produktow">
        <h2>Edytuj kategorię</h2>
        <form method="post">
            <label for="category_id_edit">ID kategorii:</label>
            <input type="number" id="category_id_edit" name="category_id" required>

            <label for="new_name">Nowa nazwa kategorii:</label>
            <input type="text" id="new_name" name="new_name" required>

            <input type="submit" name="edit" value="Edytuj Kategorię">
        </form>
    </section>

    <section class="lista-produktow">
        <h2>Lista kategorii</h2>
        <div class="produkt">
            <?php
            // Wyświetlanie kategorii
            $categoryManager->pokazKategorie();
            ?>
        </div>
    </section>
</body>
</html>
