<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$date_naissance = $_POST['date_naissance'];
$telephone = $_POST['telephone'];
$adresse = $_POST['adresse'];
$groupe_sanguin = $_POST['groupe_sanguin'];
$date_dernieres_regles = $_POST['date_dernieres_regles'];
$date_prevue_accouchement = $_POST['date_prevue_accouchement'];
$nombre_grossesses_precedentes = $_POST['nombre_grossesses_precedentes'];
$antecedents_medicaux = $_POST['antecedents_medicaux'];
$allergies = $_POST['allergies'];

// Mettre à jour la base de données
$sql = "UPDATE femmes_enceintes SET 
    date_naissance='$date_naissance', 
    telephone='$telephone', 
    adresse='$adresse', 
    groupe_sanguin='$groupe_sanguin',
    date_dernieres_regles='$date_dernieres_regles',
    date_prevue_accouchement='$date_prevue_accouchement',
    nombre_grossesses_precedentes='$nombre_grossesses_precedentes',
    antecedents_medicaux='$antecedents_medicaux',
    allergies='$allergies',
    profil_complet=1 
    WHERE id=$user_id";

if ($conn->query($sql) === TRUE) {
// Après avoir mis à jour le profil dans la base de données
$_SESSION['profil_complet'] = 1; // 1 signifie que le profil est complet
    echo "Profil mis à jour avec succès. <a href='profil.php'>Retour au tableau de bord</a>";
} else {
    echo "Erreur : " . $conn->error;
}

$conn->close();
?>