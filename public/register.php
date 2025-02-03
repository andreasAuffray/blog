<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier que les champs sont remplis
    if (empty($username) || empty($password)) {
        $error = "Tous les champs doivent être remplis.";
    } else {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = "Ce nom d'utilisateur est déjà pris.";
        } else {
            // Insérer l'utilisateur 
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $password]);

            // Rediriger vers la page de connexion après la création du compte
            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte</title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Créer un compte</h2>

        <!-- Afficher l'erreur s'il y en a -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Créer le compte</button>
        </form>

        <p class="mt-3">Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
