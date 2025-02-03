<?php
session_start();

// Vérifier si l'utilisateur est connecté et est modérateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'moderateur') {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprimer le post
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Supprimer les commentaires associés au post
    $stmt = $pdo->prepare("DELETE FROM comments WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $id]);

    // Rediriger vers la page d'accueil
    header('Location: index.php');
    exit();
} else {
    header('Location: index.php');
    exit();
}
