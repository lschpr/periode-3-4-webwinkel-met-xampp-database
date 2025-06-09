<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Inloggen</h2>

        <!-- Toon foutmeldingen -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="verwerk_login.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

            <div class="mb-3">
                <label for="email" class="form-label">E-mailadres:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="wachtwoord" class="form-label">Wachtwoord:</label>
                <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary">Inloggen</button>
        </form>
    </div>
</body>
</html>
