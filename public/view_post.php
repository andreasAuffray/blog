<?php
session_start();
require 'config.php';

// Récupérer l'ID du post
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$post_id = intval($_GET['id']);

// Récupérer les détails du post
$stmt = $pdo->prepare("SELECT id,title,description,content,DATE_FORMAT(created_at,'%d/%m/%Y %H-%i-%s') as date_formater FROM posts WHERE id = :id");
$stmt->execute(['id' => $post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post non trouvé.";
    exit();
}

// Récupérer les commentaires associés au post
$stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <div>
                <?php if (($_SESSION['role'] ?? 'guest')=== 'guest'): ?>
                    <a href="login.php" class="btn btn-primary">Se connecter</a>
                <?php else: ?>
                    <a href="?logout=1" class="btn btn-danger">Se déconnecter</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($post['title']) ?></h5>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <p><strong>Publié le :</strong> <?= htmlspecialchars($post['date_formater']) ?></p>

                        <hr>
                        <h6>Commentaires :</h6>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <p><?= htmlspecialchars($comment['content']) ?></p>
                            </div>
                        <?php endforeach; ?>

                        <!-- Formulaire pour ajouter un commentaire -->
                        <?php if (($_SESSION['role'] ?? 'guest')!== 'guest'): ?>
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
            </div>
        </div>
    </div>
    <a href="index.php" class="btn btn-secondary">Retour</a>
</body>
</html>
