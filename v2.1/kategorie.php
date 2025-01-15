<?php

class Kategorie {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function DodajKategorie($nazwa, $matka = 0) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nazwa, matka) VALUES (?, ?)");
        $stmt->execute([$nazwa, $matka]);
    }

    public function UsunKategorie($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function EdytujKategorie($id, $nowaNazwa) {
        $stmt = $this->pdo->prepare("UPDATE categories SET nazwa = ? WHERE id = ?");
        $stmt->execute([$nowaNazwa, $id]);
    }

    public function PokazKategorie() {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        $kategorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        echo '<ul>';
        $this->PokazHierarchieHTML($kategorie);
        echo '</ul>';
    }
    
    private function PokazHierarchieHTML($kategorie, $matka = 0, $poziom = 0) {
        foreach ($kategorie as $kategoria) {
            if ($kategoria['matka'] == $matka) {
                echo '<li>' . str_repeat('&nbsp;', $poziom * 4) . htmlspecialchars($kategoria['nazwa'], ENT_QUOTES, 'UTF-8') . '</li>';
                $this->PokazHierarchieHTML($kategorie, $kategoria['id'], $poziom + 1);
            }
        }
    }
}

class Produkty {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
        $stmt = $this->pdo->prepare("INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie]);
    }

    public function UsunProdukt($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produkty WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function EdytujProdukt($id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
        $stmt = $this->pdo->prepare("UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc_dostepnych_sztuk = ?, status_dostepnosci = ?, kategoria = ?, gabaryt = ?, zdjecie = ? WHERE id = ?");
        $stmt->execute([$tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $id]);
    }

    public function PokazProdukty() {
        $stmt = $this->pdo->query("SELECT * FROM produkty");
        $produkty = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produkty as $produkt) {
            echo "<div class='produkt'>";
            echo "<h3>" . htmlspecialchars($produkt['tytul']) . "</h3>";
            echo "<p>" . htmlspecialchars($produkt['opis']) . "</p>";
            echo "<p>Cena: " . number_format($produkt['cena_netto'], 2) . " zł</p>";
            // Formularz dodawania do koszyka
            echo '<form method="post" action="index.php">';
            echo '<input type="hidden" name="product_id" value="' . $produkt['id'] . '">';
            echo 'Ilość: <input type="number" name="quantity" value="1"><br>';
            echo '<input type="hidden" name="price" value="' . $produkt['cena_netto'] . '">';
            echo '<button type="submit" class="przycisk-kup" name="add_to_cart">Dodaj do koszyka</button>';
            echo '</form>';
            echo "</div>";
        }
    }
    
}

?>
