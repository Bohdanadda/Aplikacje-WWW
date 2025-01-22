<?php
include('cfg.php'); // Połączenie z bazą danych
require_once 'kategorie.php'; // Klasa Produkty

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produkty = new Produkty($pdo);

    // Pobierz dane z formularza
    $tytul = $_POST['tytul'] ?? null;
    $opis = $_POST['opis'] ?? null;
    $cena_netto = $_POST['cena_netto'] ?? null;
    $vat = $_POST['vat'] ?? null;
    $ilosc = $_POST['ilosc'] ?? null; // Ilość na magazynie
    $status = $_POST['status'] ?? null;
    $kategoria = $_POST['kategoria'] ?? null;
    $zdjecie = '';

    // Obsługa przesyłania zdjęcia
    if (isset($_FILES['zdjecie']) && $_FILES['zdjecie']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'img/';
        $fileName = uniqid() . '_' . basename($_FILES['zdjecie']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['zdjecie']['tmp_name'], $uploadFile)) {
            $zdjecie = $fileName;
        }
    }

    try {
        $produkty->DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, null, $zdjecie);
        $_SESSION['message'] = "Produkt został dodany.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Błąd: " . $e->getMessage();
    }
    header('Location: index.php?idp=glowna');
    exit();
}
