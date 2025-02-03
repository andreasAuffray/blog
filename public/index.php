<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur souhaite se déconnecter
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

// Récupérer le rôle et l'utilisateur
$role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'Visiteur';

// Récupérer les posts depuis la base de données
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();

// Récupérer les commentaires associés aux posts
$comments = [];
foreach ($posts as $post) {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post['id']]);
    $comments[$post['id']] = $stmt->fetchAll();
}

// Supprimer un post (si l'utilisateur est modérateur)
if ($role === 'moderateur' && isset($_GET['delete_post'])) {
    $deletePostId = intval($_GET['delete_post']);
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute(['id' => $deletePostId]);
    header('Location: index.php');
    exit();
}

// Supprimer un commentaire (si l'utilisateur est modérateur)
if ($role === 'moderateur' && isset($_GET['delete_comment'])) {
    $deleteCommentId = intval($_GET['delete_comment']);
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
    $stmt->execute(['id' => $deleteCommentId]);
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
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Blog</h2>
            <div>
                <?php if ($role === 'guest'): ?>
                    <a href="login.php" class="btn btn-primary">Se connecter</a>
                    <a href="register.php" class="btn btn-secondary">Créer un compte</a>
                <?php else: ?>
                    <a href="?logout=1" class="btn btn-danger">Se déconnecter</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-9">
                <?php foreach ($posts as $post): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($post['title']) ?></h5>
                            <p><?= htmlspecialchars($post['description']) ?></p>
                            <a href="view_post.php?id=<?= $post['id'] ?>" class="btn btn-link">Afficher plus</a>

                            <?php if ($role === 'moderateur'): ?>
                                <a href="?delete_post=<?= $post['id'] ?>" class="text-danger">Supprimer ce post</a>
                            <?php endif; ?>

                            <hr>
                            <h6>Commentaires :</h6>
                            <?php
                            if (isset($comments[$post['id']])) {
                                foreach ($comments[$post['id']] as $comment):
                            ?>
                                    <div class="comment">
                                        <p><?= htmlspecialchars($comment['content']) ?></p>
                                        <?php if ($role === 'moderateur'): ?>
                                            <a href="?delete_comment=<?= $comment['id'] ?>" class="text-danger">Supprimer ce commentaire</a>
                                        <?php endif; ?>
                                    </div>
                            <?php endforeach; } ?>

                            <!-- Formulaire pour ajouter un commentaire -->
                            <?php if ($role !== 'guest'): ?>
                                <form method="POST" action="add_comment.php">
                                    <div class="mb-3">
                                        <label for="comment_content" class="form-label">Ajouter un commentaire</label>
                                        <textarea class="form-control" id="comment_content" name="comment_content" rows="3" required></textarea>
                                    </div>
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="btn btn-primary">Envoyer le commentaire</button>
                                </form>
                            <?php else: ?>
                                <p>Veuillez vous connecter pour ajouter un commentaire.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-3">
                <div class="d-grid gap-2">
                    <?php if ($role === 'moderateur' || $role === 'ecrivain'): ?>
                        <a href="add_post.php" class="btn btn-success btn-lg">Ajouter un post</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
