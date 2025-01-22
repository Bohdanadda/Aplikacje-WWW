<?php
// Dane do połączenia z bazą danych
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

try {
    // Tworzenie połączenia PDO
    $pdo = new PDO("mysql:host=$dbhost;dbname=$baza", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

// Dane logowania do panelu admina
$login = 'admin'; // Login administratora
$pass = 'haslo123'; // Hasło administratora
?>
