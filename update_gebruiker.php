<?php
session_start();
include 'db_connect.php'; // Verbind met de database

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruikers_id'])) {
    die("Je moet ingelogd zijn om deze actie uit te voeren.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam = $_POST['voornaam'] ?? '';
    $achternaam = $_POST['achternaam'] ?? '';
    $email = $_POST['email'] ?? '';
    $gebruikers_id = $_SESSION['gebruikers_id'];

    // Validatie
    if (empty($voornaam) || empty($achternaam) || empty($email)) {
        die("Fout: Alle velden zijn verplicht.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Fout: Ongeldig e-mailadres.");
    }

    // Update-query
    $sql = "UPDATE gebruikers SET voornaam = ?, achternaam = ?, email = ? WHERE gebruiker_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssi", $voornaam, $achternaam, $email, $gebruikers_id);

        if ($stmt->execute()) {
            echo "Gegevens succesvol bijgewerkt!";
        } else {
            echo "Fout bij het bijwerken van gegevens: " . $stmt->error;
        }

        $stmt->close();
    } else {
        die("Fout bij het voorbereiden van de query: " . $conn->error);
    }
} else {
    die("Ongeldige aanvraagmethode.");
}

$conn->close();
?>
