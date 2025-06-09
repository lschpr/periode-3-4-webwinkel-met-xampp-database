<?php
session_start(); // Start de sessie

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruikers_id'])) {
    echo "Je moet ingelogd zijn om je accountpagina te bekijken.";
    exit();
}

include 'db_connect.php'; // Databaseverbinding

// Verkrijg de gebruikersinformatie uit de database
$gebruikers_id = $_SESSION['gebruikers_id'];

$sql_gebruiker = "SELECT * FROM gebruikers WHERE gebruiker_id = ?"; // Correcte kolomnaam
$stmt = $conn->prepare($sql_gebruiker);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$stmt->bind_param("i", $gebruikers_id);
$stmt->execute();
$result = $stmt->get_result();
$gebruiker = $result->fetch_assoc();
if (!$gebruiker) {
    die("Gebruiker niet gevonden of query mislukt.");
}
$stmt->close();

// Haal bestellingen op
$sql_bestellingen = "SELECT bestellingen.aantal, producten.naam AS product_naam, bestellingen.bestelling_id AS bestelling_id 
                     FROM bestellingen 
                     JOIN producten ON bestellingen.product_id = producten.product_id 
                     WHERE bestellingen.gebruiker_id = ?";
$stmt_bestellingen = $conn->prepare($sql_bestellingen);
if (!$stmt_bestellingen) {
    die("Query Error: " . $conn->error);
}
$stmt_bestellingen->bind_param("i", $gebruikers_id);
$stmt_bestellingen->execute();
$result_bestellingen = $stmt_bestellingen->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountpagina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Accountpagina</h2>
        <form action="update_gebruiker.php" method="post">
            <div class="form-group">
                <label for="voornaam">Voornaam:</label>
                <input type="text" class="form-control" id="voornaam" name="voornaam" value="<?php echo htmlspecialchars($gebruiker['voornaam'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="achternaam">Achternaam:</label>
                <input type="text" class="form-control" id="achternaam" name="achternaam" value="<?php echo htmlspecialchars($gebruiker['achternaam'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($gebruiker['email'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Gegevens bijwerken</button>
        </form>
    </div>
</body>
</html>


<?php
// Sluit de databaseverbinding
$stmt_bestellingen->close();
$conn->close();
?>
