<?php
session_start();
include 'db_connect.php'; 


if (!isset($_SESSION['gebruikers_id'])) {
    echo "Je moet ingelogd zijn om een bestelling te plaatsen.";
    exit();
}


$gebruikers_id = $_SESSION['gebruikers_id'];


foreach ($_POST as $product => $aantal) {
    if (strpos($product, 'product_') === 0 && ctype_digit($aantal) && $aantal > 0) {
        $product_id = str_replace('product_', '', $product);
        if (!ctype_digit($product_id)) {
            continue; // Ongeldige product-ID overslaan
        }
        $product_id = (int)$product_id;
        $aantal = (int)$aantal;

        // Prepared statement gebruiken hier
        $sql = "INSERT INTO bestellingen (gebruiker_id, product_id, aantal) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $gebruiker_id, $product_id, $aantal);

        if ($stmt->execute()) {
            echo "Bestelling geplaatst voor product ID: $product_id met aantal: $aantal.<br>";
        } else {
            echo "Fout bij het plaatsen van de bestelling: " . $stmt->error . "<br>";
        }

        $stmt->close();
    }
}


$conn->close();
?>
