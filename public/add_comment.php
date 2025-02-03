<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['role']) || $_SESSION['role'] === 'guest') {
    header('Location: login.php');
    exit();
}

// Ajouter un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $commentContent = $_POST['comment_content'];

    if (empty($commentContent)) {
        $error = "Le commentaire ne peut pas être vide.";
    } else {
        // Ajouter le commentaire dans la base de données
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, content, created_at) VALUES (:post_id, :content, NOW())");
        $stmt->execute(['post_id' => $postId, 'content' => $commentContent]);
        header('Location: index.php');
        exit();
    }
}
?>
