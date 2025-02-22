<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=clinique_bonheur", "root", "");

// Récupérer le nombre de comptes en attente de validation
$query = $pdo->query("SELECT COUNT(*) as total FROM femmes_enceintes WHERE est_valide = 0");
$result = $query->fetch(PDO::FETCH_ASSOC);

// Retourner le nombre en JSON
echo json_encode(['count' => $result['total']]);
?>
