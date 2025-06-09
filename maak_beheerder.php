<?php
session_start();
include 'db_connect.php';

// Debugging: Controleer sessie-inhoud
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Controleer of de gebruiker is ingelogd en een beheerder is
if (!isset($_SESSION['gebruikers_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'beheerder') {
    die("Toegang geweigerd: alleen beheerders kunnen deze pagina bekijken.");
}

// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruiker_id = $_POST['gebruiker_id'];

    // Controleer of een gebruiker_id is opgegeven
    if (empty($gebruiker_id)) {
        die("Fout: Geen gebruiker geselecteerd.");
    }

    // Update de rol van de gebruiker naar 'beheerder'
    $sql = "UPDATE gebruikers SET rol = 'beheerder' WHERE gebruiker_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Fout bij het voorbereiden van de query: " . $conn->error);
    }

    $stmt->bind_param("i", $gebruiker_id);

    if ($stmt->execute()) {
        echo "De gebruiker met ID $gebruiker_id is nu een beheerder.";
    } else {
        echo "Fout bij het wijzigen van de rol: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maak Beheerder</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Maak een Gebruiker Beheerder</h2>
        <form method="post">
            <div class="mb-3">
                <label for="gebruiker_id" class="form-label">Gebruiker ID:</label>
                <input type="number" class="form-control" id="gebruiker_id" name="gebruiker_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Maak Beheerder</button>
        </form>
    </div>
</body>
</html>
