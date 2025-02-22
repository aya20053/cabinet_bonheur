<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Initialiser la variable de recherche
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Préparer la requête SQL pour rechercher par nom de médecin ou description
$sql_notes = "
    SELECT n.note, n.date_ajout, n.description, CONCAT(m.prenom, ' ', m.nom) AS medecin
    FROM notes n
    JOIN medecins m ON n.medecin_id = m.id
    WHERE CONCAT(m.prenom, ' ', m.nom) LIKE ? OR n.description LIKE ?
    ORDER BY n.date_ajout DESC
";

$stmt = $conn->prepare($sql_notes);
$searchTerm = "%$searchTerm%"; // Pour inclure des correspondances partielles
$stmt->bind_param('ss', $searchTerm, $searchTerm); // Lier les deux paramètres
$stmt->execute();
$result_notes = $stmt->get_result();

$output = '';
if ($result_notes->num_rows > 0) {
    while ($row = $result_notes->fetch_assoc()) {
        $output .= '<div class="card mb-3">';
        $output .= '<div class="card-header">Médecin : <span class="fw-bold">' . htmlspecialchars($row['medecin']) . '</span></div>';
        $output .= '<div class="card-body"><p class="note-text">📝 "' . nl2br(htmlspecialchars($row['description'])) . '"</p>';
        $output .= '<p><a href="./uploaded_notes/' . htmlspecialchars($row['note']) . '" target="_blank" class="btn btn-secondary">Voir le PDF</a></p>';
        $output .= '</div><div class="card-footer note-footer">Ajoutée le ' . htmlspecialchars($row['date_ajout']) . '</div>';
        $output .= '</div>';
    }
} else {
    $output .= '<p class="text-center text-muted">Aucune note trouvée.</p>';
}

echo $output;

$stmt->close();
$conn->close();
?>