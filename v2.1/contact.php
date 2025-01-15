<?php
/**
 * Funkcje do obsługi formularza kontaktowego oraz zarządzania wiadomościami e-mail.
 */

/**
 * Funkcja wyświetlająca formularz kontaktowy.
 */
function PokazKontakt() {
    echo '
    <div class="formularz-kontaktowy">
        <form action="contact.php" method="post">
            <label for="name">Imię i nazwisko:</label>
            <input type="text" id="name" name="name" required><br>
            
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br>
            
            <label for="message">Wiadomość:</label><br>
            <textarea id="message" name="message" required></textarea><br>
            
            <button type="submit">Wyślij</button>
        </form>
    </div>';
}

/**
 * Funkcja wysyłająca przypomnienie hasła na adres e-mail administratora.
 */
function PrzypomnijHaslo() {
    $admin_email = "admin@example.com";
    $password = "TwojeHaslo123"; // UWAGA: Zmień na bezpieczne przechowywanie hasła!
    
    $subject = "Przypomnienie hasła";
    $message = "Twoje hasło do panelu admina to: $password";
    $headers = "From: no-reply@example.com";

    mail($admin_email, $subject, $message, $headers);
}

/**
 * Funkcja obsługująca przesyłanie wiadomości z formularza kontaktowego.
 */
function WyslijMailKontakt() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

        if (!$email) {
            echo "Nieprawidłowy adres e-mail.";
            return;
        }

        $to = "kontakt@example.com";
        $subject = "Nowa wiadomość od $name";
        $body = "Imię i nazwisko: $name\nE-mail: $email\n\nWiadomość:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo "Wiadomość została wysłana!";
        } else {
            echo "Wystąpił błąd. Spróbuj ponownie.";
        }
    }
}
?>
