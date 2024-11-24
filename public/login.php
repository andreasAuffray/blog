<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté, si oui, rediriger vers la page d'accueil
if (isset($_SESSION['role'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Liste des utilisateurs et mots de passe
    $users = [
        'admin' => '1234',
        'ecrivain' => '1234'
    ];

    // Récupérer les informations du formulaire
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérification des identifiants
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $username;  // 'admin' ou 'ecrivain'
        header('Location: index.php');
        exit();
    } else {
        $error = "Identifiants incorrects!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 300px;">
      <h3 class="text-center mb-4">Connexion</h3>
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Nom d'utilisateur</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-4">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
      </form>
    </div>
  </div>
</body>
</html>
