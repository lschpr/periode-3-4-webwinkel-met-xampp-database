<?php
session_start(); // Sessie starten

var_dump($_SESSION);
var_dump($_SESSION['gebruikers_id']);
var_dump($_SESSION['gebruikers_naam']);
var_dump(empty($_SESSION['gebruikers_id']));
var_dump(empty($_SESSION['gebruikers_naam']));

include 'db_connect.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruikers_id']) || !isset($_SESSION['gebruikers_naam'])) {
    echo ("Je moet ingelogd zijn om een review te plaatsen. <a href='login.php'>Inloggen</a>");
    exit; // Stop hier om te voorkomen dat de pagina verder laadt
}

try {
    $pdo = new PDO("mysql:host=$servernaam;dbname=$database;charset=utf8", $gebruikers_naam, $wachtwoord);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbinding mislukt: " . $e->getMessage());
}

// Review opslaan in database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['gebruikers_id']) || !isset($_SESSION['gebruikers_naam'])) {
        die("Je moet ingelogd zijn om een review te plaatsen.");
    }

    $gebruiker_id = $_SESSION['gebruikers_id']; // Haal gebruikers_id uit de sessie
    $gebruikersnaam = $_SESSION['gebruikers_naam']; // Haal gebruikers_naam uit de sessie
    $review = htmlspecialchars($_POST["review"]);

    if (!empty($review)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (gebruiker_id, gebruikersnaam, review) VALUES (?, ?, ?)");
            $stmt->execute([$gebruiker_id, $gebruikersnaam, $review]);
            echo "<p style='color: green;'>Review geplaatst!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Fout bij opslaan: " . $e->getMessage() . "</p>";
        }
    }
}

// Reviews ophalen uit de database
$reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviewpagina</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 50%; margin: auto; }
        textarea { width: 100%; height: 100px; }
        .review { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Schrijf een review</h2>
        <form method="POST">
            <!-- Laat gebruikersnaam NIET handmatig invullen, maar toon het automatisch -->
            <p>Ingelogd als: <strong><?= htmlspecialchars($_SESSION['gebruikers_naam']) ?></strong></p>
            <textarea name="review" placeholder="Schrijf je review hier..." required></textarea><br><br>
            <button type="submit">Plaatsen</button>
        </form>

        <h2>Reviews</h2>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <strong><?= htmlspecialchars($review["gebruikersnaam"]) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($review["review"])) ?></p>
                <small><?= $review["created_at"] ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
