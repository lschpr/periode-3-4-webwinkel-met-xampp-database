<?php
session_start();
include 'db_connect.php'; // Verbind met de database

// Controleer of de gebruiker een beheerder is
if (!isset($_SESSION['gebruikers_id']) || $_SESSION['rol'] !== 'beheerder') {
    die("Toegang geweigerd: alleen beheerders kunnen deze pagina bekijken.");
}

// Acties verwerken: toevoegen, wijzigen, verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'];
    if ($actie === 'toevoegen') {
        $naam = $_POST['naam'];
        $beschrijving = $_POST['beschrijving'];
        $prijs = $_POST['prijs'];
        $voorraad = $_POST['voorraad'];

        $sql = "INSERT INTO producten (naam, beschrijving, prijs, voorraad) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $naam, $beschrijving, $prijs, $voorraad);
        $stmt->execute();
        $stmt->close();
        echo "Product toegevoegd!";
    } elseif ($actie === 'wijzigen') {
        $product_id = $_POST['product_id'];
        $naam = $_POST['naam'];
        $beschrijving = $_POST['beschrijving'];
        $prijs = $_POST['prijs'];
        $voorraad = $_POST['voorraad'];

        $sql = "UPDATE producten SET naam = ?, beschrijving = ?, prijs = ?, voorraad = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $naam, $beschrijving, $prijs, $voorraad, $product_id);
        $stmt->execute();
        $stmt->close();
        echo "Product gewijzigd!";
    } elseif ($actie === 'verwijderen') {
        $product_id = $_POST['product_id'];

        $sql = "DELETE FROM producten WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
        echo "Product verwijderd!";
    }
}

// Haal alle producten op
$result = $conn->query("SELECT * FROM producten");
$producten = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productbeheer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Productbeheer</h2>

        <!-- Product Toevoegen -->
        <h3>Product Toevoegen</h3>
        <form method="post">
            <input type="hidden" name="actie" value="toevoegen">
            <div class="mb-3">
                <label for="naam" class="form-label">Naam</label>
                <input type="text" class="form-control" id="naam" name="naam" required>
            </div>
            <div class="mb-3">
                <label for="beschrijving" class="form-label">Beschrijving</label>
                <textarea class="form-control" id="beschrijving" name="beschrijving" required></textarea>
            </div>
            <div class="mb-3">
                <label for="prijs" class="form-label">Prijs</label>
                <input type="number" step="0.01" class="form-control" id="prijs" name="prijs" required>
            </div>
            <div class="mb-3">
                <label for="voorraad" class="form-label">Voorraad</label>
                <input type="number" class="form-control" id="voorraad" name="voorraad" required>
            </div>
            <button type="submit" class="btn btn-success">Toevoegen</button>
        </form>

        <!-- Producten Beheren -->
        <h3 class="mt-5">Bestaande Producten</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Beschrijving</th>
                    <th>Prijs</th>
                    <th>Voorraad</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($producten as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($product['naam']); ?></td>
                        <td><?php echo htmlspecialchars($product['beschrijving']); ?></td>
                        <td>&euro;<?php echo htmlspecialchars(number_format($product['prijs'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($product['voorraad']); ?></td>
                        <td>
                            <!-- Wijzigen -->
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="actie" value="wijzigen">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <input type="text" name="naam" value="<?php echo htmlspecialchars($product['naam']); ?>" required>
                                <input type="text" name="beschrijving" value="<?php echo htmlspecialchars($product['beschrijving']); ?>" required>
                                <input type="number" step="0.01" name="prijs" value="<?php echo htmlspecialchars($product['prijs']); ?>" required>
                                <input type="number" name="voorraad" value="<?php echo htmlspecialchars($product['voorraad']); ?>" required>
                                <button type="submit" class="btn btn-primary">Wijzigen</button>
                            </form>
                            <!-- Verwijderen -->
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="actie" value="verwijderen">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <button type="submit" class="btn btn-danger">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
