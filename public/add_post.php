<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'ecrivain'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les informations du formulaire
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    // Ajouter le post à la session
    $_SESSION['posts'][] = ['title' => $title, 'content' => $content];

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un poste</title>
  <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2>Ajouter un nouveau poste</h2>
    <form method="POST">
      <div class="mb-3">
        <label for="title" class="form-label">Titre</label>
        <input type="text" class="form-control" id="title" name="title" required>
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Contenu</label>
        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
      </div>
      <button type="submit" class="btn btn-success">Créer le poste</button>
    </form>
  </div>
</body>
</html>
