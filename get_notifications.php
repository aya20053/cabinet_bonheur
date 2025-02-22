<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=ton_db", "utilisateur", "motdepasse");

// Récupérer le nombre de demandes en attente
$query = $pdo->query("SELECT COUNT(*) as total FROM demandes WHERE statut = 'en_attente'");
$result = $query->fetch(PDO::FETCH_ASSOC);

// Retourner le nombre en JSON
echo json_encode(['count' => $result['total']]);
?>
