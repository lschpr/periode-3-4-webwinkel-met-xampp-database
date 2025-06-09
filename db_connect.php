<?php
$servername = "localhost";
$username = "root"; // Standaard MySQL-gebruiker bij XAMPP
$password = ""; // Standaard MySQL-wachtwoord is leeg bij XAMPP
$database = "project3"; // De naam van je geïmporteerde database

// Maak een verbinding
$conn = new mysqli($servername, $username, $password, $database);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
echo "Verbinding succesvol!";
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (strlen($username) < 3) {
        $errors[] = "De gebruikersnaam moet minstens 3 tekens lang zijn.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Voer een geldig e-mailadres in.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "De wachtwoorden komen niet overeen.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Het wachtwoord moet minstens 6 tekens lang zijn.";
    }

    if (empty($errors)) {

        echo "<div class='alert alert-success'>Registratie geslaagd!</div>";
    } else {
    
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
