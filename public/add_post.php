<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur a le rôle pour ajouter un post
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'ecrivain' && $_SESSION['role'] !== 'moderateur')) {
    header('Location: login.php');
    exit();
}

// Ajouter un post dans la base de données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';

    // Validation de l'entrée
    if (empty($title) || empty($description) || empty($content)) {
        $error = "Tous les champs sont requis.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, description, content, created_at) VALUES (:title, :description, :content, NOW())");
        $stmt->execute(['title' => $title, 'description' => $description, 'content' => $content]);
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un post</title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Ajouter un nouveau post</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Publier le post</button>
        </form>
    </div>
</body>
</html>
