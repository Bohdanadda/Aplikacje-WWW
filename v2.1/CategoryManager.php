<?php
class categoryManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Dodaj kategorię
    public function dodajKategorie($nazwa, $matka = null) {
        try {
            // Sprawdź, czy matka istnieje, jeśli została podana
            if ($matka !== null) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categories WHERE id = ?");
                $stmt->execute([$matka]);

                if ($stmt->fetchColumn() == 0) {
                    // Jeśli nadrzędna kategoria nie istnieje, utwórz ją
                    $this->dodajKategorie("Kategoria", null);
                    $matka = $this->pdo->lastInsertId(); // Pobierz ID nowo utworzonej kategorii nadrzędnej
                    echo "Utworzono brakującą kategorię nadrzędną o ID $matka.<br>";
                }
            }

            // Dodaj kategorię
            $stmt = $this->pdo->prepare("INSERT INTO categories (nazwa, matka) VALUES (?, ?)");
            $stmt->execute([$nazwa, $matka]);

            echo "Kategoria \"$nazwa\" została dodana pomyślnie.<br>";
        } catch (Exception $e) {
            echo "Błąd dodawania kategorii: " . $e->getMessage() . "<br>";
        }
    }

    // Usuń kategorię (wraz z podrzędnymi)
    public function usunKategorie($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ? OR matka = ?");
            $stmt->execute([$id, $id]);

            echo "Kategoria o ID $id została usunięta pomyślnie.<br>";
        } catch (PDOException $e) {
            echo "Błąd usuwania kategorii: " . $e->getMessage() . "<br>";
        }
    }

    // Edytuj nazwę kategorii
    public function edytujKategorie($id, $nowaNazwa) {
        try {
            $stmt = $this->pdo->prepare("UPDATE categories SET nazwa = ? WHERE id = ?");
            $stmt->execute([$nowaNazwa, $id]);

            echo "Kategoria o ID $id została zaktualizowana na \"$nowaNazwa\".<br>";
        } catch (PDOException $e) {
            echo "Błąd edycji kategorii: " . $e->getMessage() . "<br>";
        }
    }

    // Wyświetl kategorie w hierarchii
    public function pokazKategorie() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY matka ASC, id ASC");
            $kategorie = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($kategorie)) {
                echo "Brak kategorii do wyświetlenia.<br>";
                return;
            }

            // Wywołanie funkcji rekurencyjnej dla hierarchicznego wyświetlania kategorii
            $this->pokazHierarchieHTML($kategorie);
        } catch (PDOException $e) {
            echo "Błąd wyświetlania kategorii: " . $e->getMessage() . "<br>";
        }
    }

    // Funkcja rekurencyjna do wyświetlania hierarchii kategorii
    private function pokazHierarchieHTML($kategorie, $matka = null, $poziom = 0) {
        foreach ($kategorie as $kategoria) {
            if ($kategoria['matka'] === $matka) {
                echo str_repeat('&nbsp;&nbsp;', $poziom) . htmlspecialchars($kategoria['nazwa']) . " (ID: {$kategoria['id']})<br>";
                $this->pokazHierarchieHTML($kategorie, $kategoria['id'], $poziom + 1);
            }
        }
    }
}
?>
