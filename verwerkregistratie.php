<?php
// Verbinding met de database maken
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $naam = $_POST['username'] ?? null;
    $voornaam = $_POST['voornaam'] ?? null;
    $achternaam = $_POST['achternaam'] ?? null;
    $email = $_POST['email'] ?? null;
    $wachtwoord = $_POST['password'] ?? null;
    $bevestigWachtwoord = $_POST['confirm_password'] ?? null;

    // Controleer of alle velden ingevuld zijn
    if (empty($naam) || empty($voornaam) || empty($achternaam) || empty($email) || empty($wachtwoord) || empty($bevestigWachtwoord)) {
        echo "Alle velden zijn verplicht!";
        exit;
    }

    // Valideer wachtwoordlengte
    if (strlen($wachtwoord) < 8) {
        echo "Het wachtwoord moet minstens 8 tekens lang zijn!";
        exit;
    }

    // Controleer of wachtwoorden overeenkomen
    if ($wachtwoord === $bevestigWachtwoord) {
        // Beveilig het wachtwoord met password_hash()
        $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

        // Gebruik een prepared statement om gegevens veilig in te voegen
        $sql = "INSERT INTO gebruikers (gebruikersnaam, voornaam, achternaam, email, wachtwoord) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssss", $naam, $voornaam, $achternaam, $email, $hashedPassword);

            if ($stmt->execute()) {
                echo "Registratie succesvol!";
            } else {
                echo "Fout bij het registreren: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Er is een fout opgetreden bij het voorbereiden van de query: " . $conn->error;
        }
    } else {
        echo "Wachtwoorden komen niet overeen!";
    }

    // Sluit de databaseverbinding
    $conn->close();
}
?>
