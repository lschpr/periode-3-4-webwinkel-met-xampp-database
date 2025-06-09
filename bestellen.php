<?php
session_start(); // Start de sessie om te controleren of de gebruiker is ingelogd
include 'db_connect.php'; // Verbind met de database

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruikers_id'])) {
    echo "Je moet ingelogd zijn om een bestelling te plaatsen.";
    exit();
}

// Haal alle producten op uit de database
$sql = "SELECT * FROM producten";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelformulier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Bestel Producten</h2>
        <form action="verwerk_bestelling.php" method="post">
            <?php
            if ($result && $result->num_rows > 0) {
                // Producten weergeven als een lijst
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="form-group">';
                    echo '<label>' . htmlspecialchars($row['naam']) . ' - €' . htmlspecialchars($row['prijs']) . '</label>';
                    echo '<input type="number" class="form-control" name="product_' . $row['product_id'] . '" min="0" placeholder="Aantal">';
                    echo '</div>';
                }
            } else {
                echo "<p>Geen producten gevonden.</p>";
            }
            ?>
            <button type="submit" class="btn btn-primary">Bestelling Plaatsen</button>
        </form>
    </div>
</body>
</html>
