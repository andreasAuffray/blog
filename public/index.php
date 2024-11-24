<?php
session_start();

// Gestion des rôles
$role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'Visiteur';

// Gestion de la liste des postes
if (!isset($_SESSION['posts'])) {
    $_SESSION['posts'] = [
        ['title' => 'Poste le plus populaire', 'content' => 'Contenu du poste populaire.'],
        ['title' => 'Poste 1', 'content' => 'Contenu du poste 1.'],
        ['title' => 'Poste 2', 'content' => 'Contenu du poste 2.']
    ];
}

// Supprimer un poste
if ($role === 'admin' && isset($_GET['delete'])) {
    $deleteIndex = intval($_GET['delete']);
    array_splice($_SESSION['posts'], $deleteIndex, 1);
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <style>
    .delete-btn { cursor: pointer; color: red; font-weight: bold; margin-left: 10px; display: none; }
    .delete-mode .delete-btn { display: inline; }
  </style>
</head>
<body>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Blog Logo</h2>
      <div>
        <?php if ($role === 'guest'): ?>
          <a href="login.php" class="btn btn-primary">Se connecter</a>
        <?php else: ?>
          <span class="me-3">Bienvenue, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</span>
          <a href="?logout=1" class="btn btn-danger">Se déconnecter</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-9">
        <?php foreach ($_SESSION['posts'] as $index => $post): ?>
          <div class="card mb-4">
            <div class="card-body d-flex justify-content-between">
              <div>
                <h5><?= htmlspecialchars($post['title']) ?></h5>
                <p><?= htmlspecialchars($post['content']) ?></p>
              </div>
              <?php if ($role === 'admin' || $role === 'ecrivain'): ?>
                <a href="?delete=<?= $index ?>" class="delete-btn">✖</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="col-md-3">
        <div class="d-grid gap-2">
          <?php if (in_array($role, ['admin', 'ecrivain'])): ?>
            <a href="add_post.php" class="btn btn-success btn-lg">Ajouter un poste</a>
          <?php endif; ?>
          <?php if ($role === 'admin'): ?>
            <button id="delete-mode-btn" class="btn btn-danger btn-lg">Activer mode suppression</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('delete-mode-btn')?.addEventListener('click', () => {
        document.body.classList.toggle('delete-mode');
    });
  </script>
</body>
</html>

