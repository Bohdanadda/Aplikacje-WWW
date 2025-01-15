<?php
include('cfg.php'); // Używa połączenia z bazą danych zdefiniowanego w cfg.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'kategorie.php'; // Zakładam, że klasy są w tym samym pliku
    $produkty = new Produkty($pdo);

    $tytul = $_POST['tytul'];
    $opis = $_POST['opis'];
    $cena_netto = $_POST['cena_netto'];
    $vat = $_POST['vat'];
    $ilosc = $_POST['ilosc'];
    $status = $_POST['status'];
    $kategoria = $_POST['kategoria'];
    $zdjecie = $_POST['zdjecie'];

    try {
        $produkty->DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
        $_SESSION['message'] = "Produkt został pomyślnie dodany.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Nie udało się dodać produktu: " . $e->getMessage();
    }
    header('Location: index.php?idp=glowna'); // Przekierowanie z powrotem na stronę główną
    exit();
}
?>
