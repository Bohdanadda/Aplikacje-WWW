<?php
/**
 * Skrypt: Wyświetlanie informacji i warunkowe sprawdzanie zmiennej.
 */

// Dane użytkownika
$nr_indeksu = '169403';
$nrGrupy = 'ISI 1';

// Wyświetlenie informacji o autorze i grupie
echo 'Bohdan Andreiev | Indeks: ' . $nr_indeksu . ' | Grupa: ' . $nrGrupy . '<br/><br/>';

// Informacja o użyciu metody include()
echo 'Zastosowanie metody include():<br/><br/>';

// Przykład warunku sprawdzającego wartość zmiennej
$a = 6; // Zmienna do sprawdzenia

// Sprawdzenie wartości zmiennej $a
if ($a > 5) {
    echo "A jest większa od 5";
} elseif ($a == 5) {
    echo "A jest równa 5";
} else {
    echo "A nie jest większa od 5";
}
?>
