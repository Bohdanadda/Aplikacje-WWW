<?php

function FormularzLogowania() 
{
    echo '<form method="post" action="">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" required>
            <br>
            <label for="pass">Hasło:</label>
            <input type="password" id="pass" name="pass" required>
            <br>
            <input type="submit" value="Zaloguj">
          </form>';
}

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if ($_POST['login'] === $login && $_POST['pass'] === $pass) {
        $_SESSION['logged_in'] = true;
    } else {
        echo 'Niepoprawny login lub hasło.';
        FormularzLogowania();
    }
}
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) 
{
    FormularzLogowania();
    exit;
}

function ListaPodstron($conn) {
    $query = "SELECT id, title FROM pages";
    $result = $conn->query($query);
    echo '<table>';
    while ($row = $result->fetch_assoc()) 
    {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['title'] . '</td>';
        echo '<td><a href="edit.php?id=' . $row['id'] . '">Edytuj</a></td>';
        echo '<td><a href="delete.php?id=' . $row['id'] . '">Usuń</a></td>';
        echo '</tr>';
    }
    echo '</table>';
}

function EdytujPodstrone($conn, $id) 
{
    $query = "SELECT * FROM pages WHERE id = $id LIMIT 1";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo '<form method="post" action="">
            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" value="' . $row['title'] . '">
            <br>
            <label for="content">Treść:</label>
            <textarea id="content" name="content">' . $row['content'] . '</textarea>
            <br>
            <label for="active">Aktywna:</label>
            <input type="checkbox" id="active" name="active"' . ($row['active'] ? ' checked' : '') . '>
            <br>
            <input type="submit" value="Zapisz">
          </form>';
}

function DodajNowaPodstrone($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $active = isset($_POST['active']) ? 1 : 0;
        $query = "INSERT INTO pages (title, content, active) VALUES ('$title', '$content', $active)";
        $conn->query($query);
    }
    echo '<form method="post" action="">
            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="content">Treść:</label>
            <textarea id="content" name="content" required></textarea>
            <br>
            <label for="active">Aktywna:</label>
            <input type="checkbox" id="active" name="active">
            <br>
            <input type="submit" value="Dodaj">
          </form>';
}

function UsunPodstrone($conn, $id) {
    $query = "DELETE FROM pages WHERE id = $id LIMIT 1";
    $conn->query($query);
    echo 'Podstrona została usunięta.';
}




?>