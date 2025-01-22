<?php

require_once '../cfg.php';

session_start();

// Funkcja wyświetlająca formularz logowania
function FormularzLogowania() {
    echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css"> <!-- Link do pliku CSS -->
    <title>Logowanie</title>
</head>
<body>
<div class="formularz-kontaktowy"> <!-- Klasa dla stylizacji całego formularza -->
    <form method="post" action="admin.php">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" class="input-formularz" required><br>
        <label for="pass">Hasło:</label>
        <input type="password" id="pass" name="pass" class="input-formularz" required><br>
        <input type="submit" name="submit_login" class="przycisk-kup" value="Zaloguj">
    </form>
</div>
</body>
</html>';
}


// Obsługa logowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_login'])) {
    if ($_POST['login'] === $login && $_POST['pass'] === $pass) {
        $_SESSION['logged_in'] = true;
        header('Location: http://localhost/v2.1/index.php'); // Przekierowanie na stronę administracji
        exit;
    } else {
        echo 'Niepoprawny login lub hasło.';
        FormularzLogowania();
        exit;
    }
}

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    FormularzLogowania();
    exit;
}

// Reszta kodu...



// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    FormularzLogowania();
    exit;
}

// Funkcja wyświetlająca listę podstron
function ListaPodstron($pdo) {
    $stmt = $pdo->query("SELECT id, title FROM pages");
    echo '<table class="tabela-lista">
        <thead>
            <tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>
        </thead>
        <tbody>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>
            <td>' . htmlspecialchars($row['id']) . '</td>
            <td>' . htmlspecialchars($row['title']) . '</td>
            <td>
                <a href="admin.php?action=edit&id=' . $row['id'] . '" class="btn-akcja">Edytuj</a>
                <a href="admin.php?action=delete&id=' . $row['id'] . '" class="btn-akcja btn-delete">Usuń</a>
            </td>
          </tr>';
}
echo '</tbody></table>';

}

// Funkcja edytująca podstronę
function EdytujPodstrone($pdo, $id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
        $active = isset($_POST['active']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE pages SET title = ?, content = ?, active = ? WHERE id = ?");
        $stmt->execute([$title, $content, $active, $id]);

        echo 'Podstrona została zaktualizowana.';
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<form method="post" action="" class="formularz-dodawania-produktow">
            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" value="' . htmlspecialchars($row['title']) . '" required><br>
            <label for="content">Treść:</label>
            <textarea id="content" name="content" required>' . htmlspecialchars($row['content']) . '</textarea><br>
            <label for="active">Aktywna:</label>
            <input type="checkbox" id="active" name="active"' . ($row['active'] ? ' checked' : '') . '><br>
            <input type="submit" value="Zapisz zmiany">
          </form>';
}

// Funkcja dodająca nową podstronę
function DodajNowaPodstrone($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
        $active = isset($_POST['active']) ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO pages (title, content, active) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $active]);

        echo 'Podstrona została dodana.';
        return;
    }

    echo '<form method="post" action="" class="formularz-dodawania-produktow">
        <label for="title">Tytuł:</label>
        <input type="text" id="title" name="title" required>
        <label for="content">Treść:</label>
        <textarea id="content" name="content" required></textarea>
        <label for="active">Aktywna:</label>
        <input type="checkbox" id="active" name="active">
        <input type="submit" value="Dodaj podstronę">
      </form>';


}

// Funkcja usuwająca podstronę
function UsunPodstrone($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM pages WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    echo 'Podstrona została usunięta.';
}

// Router dla akcji
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            ListaPodstron($pdo);
            break;
        case 'add':
            DodajNowaPodstrone($pdo);
            break;
        case 'edit':
            if (isset($_GET['id'])) {
                EdytujPodstrone($pdo, $_GET['id']);
            } else {
                echo 'Brak ID podstrony do edycji.';
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                UsunPodstrone($pdo, $_GET['id']);
            } else {
                echo 'Brak ID podstrony do usunięcia.';
            }
            break;
        case 'logout':
            session_destroy();
            header('Location: admin.php');
            exit;
    }
} else {
    echo '<nav>
        <div class="meni">
            <a href="http://localhost/v2.1/index.php">Strona główna</a>
            <a href="admin.php?action=list">Lista podstron</a>
            <a href="admin.php?action=add">Dodaj podstronę</a>
            <a href="admin.php?action=logout" class="logout">Wyloguj</a>
        </div>
      </nav>';


}
?>
