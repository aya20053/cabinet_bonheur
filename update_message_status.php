<?php
// Vérifier que la requête est en POST et que les données sont envoyées en JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['statut'])) {
        // Connexion à la base de données
        try {
            $dsn = "mysql:host=localhost;dbname=clinique_bonheur;charset=utf8mb4";
            $user = "root";
            $pass = "";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            die("Échec de la connexion à la base de données : " . $e->getMessage());
        }

        // Mise à jour du statut des messages
        $statut = $data['statut'];
        $query = "UPDATE messages SET statut = :statut WHERE statut = 'non lu'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':statut', $statut);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
} else {
    echo json_encode(['success' => false]);
}
?>
