<?php
session_start();
include 'db_connect.php'; // Verbind met de database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    // Validatie van invoer
    if (!$email || !$wachtwoord) {
        header("Location: login.php?error=Vul alle velden in.");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=Ongeldig e-mailadres.");
        exit();
    }

    // Bereid de SQL-query voor
    $sql = "SELECT gebruiker_id, voornaam, achternaam, rol, wachtwoord FROM gebruikers WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Query-fout: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($gebruiker_id, $voornaam, $achternaam, $rol, $hashedPassword);
    $stmt->fetch();

    // Controleer of het wachtwoord klopt
    if ($hashedPassword && password_verify($wachtwoord, $hashedPassword)) {
        // Login succesvol: sla gegevens op in de sessie
        $_SESSION['gebruikers_id'] = $gebruiker_id;
        $_SESSION['gebruikers_naam'] = $voornaam . " " . $achternaam;
        $_SESSION['rol'] = $rol;

        // Debugging om te controleren of alles goed werkt
        echo "Login succesvol! Sessie-inhoud:<br>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

        exit();
    } else {
        // Ongeldige inloggegevens
        header("Location: login.php?error=Ongeldige inloggegevens.");
        exit();
    }

    $stmt->close();
} else {
    echo "Ongeldige aanvraagmethode.";
}

$conn->close();
?>
