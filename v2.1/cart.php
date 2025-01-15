<?php

class Cart {
    public function addToCart($product_id, $quantity, $price) {
        $total_price = $price * $quantity; // Oblicz całkowitą cenę
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'quantity' => 0,
                'price' => $price
            ];
        }
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }

    public function removeFromCart($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function showCart() {
        $total = 0;
        echo "<h1>Koszyk</h1>";
        echo "<table border='1' style='width:100%; text-align:center;'>";
        echo "<tr><th>Produkt</th><th>Ilość</th><th>Cena za sztukę</th><th>Cena całkowita</th><th>Akcje</th></tr>";
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $details) {
                $line_cost = $details['price'] * $details['quantity'];
                echo "<tr>";
                echo "<td>{$product_id}</td>";
                echo "<td>{$details['quantity']}</td>";
                echo "<td>" . number_format($details['price'], 2) . " zł</td>";
                echo "<td>" . number_format($line_cost, 2) . " zł</td>";
                echo "<td><a href='index.php?idp=koszyk&remove={$product_id}'>Usuń</a></td>";
                echo "</tr>";
                $total += $line_cost;
            }
            echo "<tr><th colspan='3'>Łącznie:</th><th>" . number_format($total, 2) . " zł</th><th></th></tr>";
        } else {
            echo "<tr><td colspan='5'>Koszyk jest pusty</td></tr>";
        }
        echo "</table>";
    }
}



$cart = new Cart();
?>
