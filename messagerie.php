<?php
session_start();
ob_start();

include 'menu.php';

// Connexion à la base de données
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Accès non autorisé. Veuillez vous connecter.");
}

$femme_id = $_SESSION['user_id'];

// Récupérer le nom de la femme enceinte
$sql_patient = $conn->prepare("SELECT CONCAT(prenom, ' ', nom) AS patient FROM femmes_enceintes WHERE id = ?");
$sql_patient->bind_param("i", $femme_id);
$sql_patient->execute();
$result_patient = $sql_patient->get_result();
$patient_name = $result_patient->num_rows > 0 ? $result_patient->fetch_assoc()['patient'] : "";

// Récupérer les médecins disponibles
$sql_medecins = "SELECT id, nom FROM medecins";
$result_medecins = $conn->query($sql_medecins);

// Envoi du message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $message = $conn->real_escape_string(trim($_POST['message']));
    $medecin_id = (int)$_POST['medecin_id'];

    // Vérifier si le message a déjà été envoyé
    $sql_check = $conn->prepare("SELECT * FROM messages WHERE message = ? AND femme_id = ? AND medecin_id = ?");
    $sql_check->bind_param("sii", $message, $femme_id, $medecin_id);
    $sql_check->execute();
    $result_check = $sql_check->get_result();

    if ($result_check->num_rows == 0) {
        $sql_message = $conn->prepare("INSERT INTO messages (message, femme_id, medecin_id) VALUES (?, ?, ?)");
        $sql_message->bind_param("sii", $message, $femme_id, $medecin_id);
        
        if ($sql_message->execute()) {
            echo "<p>Message envoyé avec succès !</p>";
        } else {
            echo "<p>Erreur lors de l'envoi du message : " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Le message est déjà envoyé.</p>";
    }
}

// Historique des messages, y compris les réponses
$sql_historique = $conn->prepare("SELECT m.id, m.message, m.date_envoi, m.medecin_id, md.nom AS medecin, m.repondre FROM messages m JOIN medecins md ON m.medecin_id = md.id WHERE m.femme_id = ? ORDER BY m.date_envoi DESC");
$sql_historique->bind_param("i", $femme_id);
$sql_historique->execute();
$result_historique = $sql_historique->get_result();

// Mettre à jour le message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_message'])) {
    $message_id = (int)$_GET['edit'];
    $new_message = $conn->real_escape_string(trim($_POST['message']));
    $new_medecin_id = (int)$_POST['medecin_id'];

    $sql_update_message = $conn->prepare("UPDATE messages SET message = ?, medecin_id = ? WHERE id = ? AND femme_id = ?");
    $sql_update_message->bind_param("siii", $new_message, $new_medecin_id, $message_id, $femme_id);

    if ($sql_update_message->execute()) {
        // Redirection après mise à jour
        header("Location: messagerie.php");
        exit();
    } else {
        echo "<p>Erreur lors de la mise à jour du message : " . $conn->error . "</p>";
    }
}

// Supprimer un message
if (isset($_GET['delete'])) {
    $message_id = (int)$_GET['delete'];

    $sql_delete_message = $conn->prepare("DELETE FROM messages WHERE id = ? AND femme_id = ?");
    $sql_delete_message->bind_param("ii", $message_id, $femme_id);

    if ($sql_delete_message->execute()) {
        echo "<p>Message supprimé avec succès !</p>";
    } else {
        echo "<p>Erreur lors de la suppression du message : " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
body {
    background-image: url('1.jpg');
    background-size: cover;
    background-position: center;
}.btn-container {
    display: flex;
    justify-content: center; /* Ou 'flex-start' pour l'aligner à gauche */
    gap: 10px; /* Espacement entre les boutons */
    margin-top: 20px;
}

.btn-container button {
    margin: 0; /* Enlève les marges automatiques */
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #B39188;
    background: #872341;
    color: white;
    cursor: pointer;
    display: inline-block; /* Le bouton reste à côté des autres éléments */
}


        
        .btn-container button:hover {
            background: #B39188;
            color: #872341;
        }

.form-container {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
}

.form-container input, .form-container textarea, .form-container select, .form-container button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}
.form-container textarea{
        width: 96%;

}
.form-container button {
           background: #872341;
    color: white;
    cursor: pointer;
}

.form-container button:hover {
            background-color: #b39188;
}
.message-history {
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: left;
            margin-top: 20px;
            color: #333;
        }
       .message-item {
            margin: 10px 0;
            font-size: 16px;
            padding: 10px;
            background: #F8EDEB;
            border-left: 5px solid #872341;
            border-radius: 5px;
        }


.message-history h1, h2 {
    text-align: center;
    margin: 20px 0;
}


.message-content {
    flex: 1;
}

.btn-container {
    display: flex;
    gap: 10px;
}

.btn-edit, .btn-delete {
    text-decoration: none;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn-edit {
    background-color: #B39188;
}

.btn-edit:hover {
    background-color: #872341;
}

.btn-delete {
    background-color: #f44336;
}

.btn-delete:hover {
    background-color: #e53935;
}

.reply {
    background: #e3f2fd;
    padding: 10px;
    border-radius: 5px;
    margin-top: 5px;
    border-left: 5px solid #2196f3;
}


.btn-edit {
    background-color: #9DC08B;
}

.btn-delete {
    background-color: #e74c3c;
}
.btn-edit,.btn-delete:hover {
    opacity: 0.9;
}

.back-button a {
            width: 100%;
            font-weight: bold;
            padding: 12px;
           background: #872341;
            color: #872341;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }
        .back-button a:hover {
            background-color: #b39188;
          
        }
        .back-button {
            display: flex;
            justify-content: flex-start; /* Aligner à gauche */
            margin-top: 20px;
        }
        h2 {
            text-align: center;
            color: #872341;
        }

          @media screen and (max-width: 768px) {
    body {
        background-repeat: no-repeat; /* Empêcher la répétition */
        background-position: center top; /* Garder l'image à son origine */
            background-image: url('ee.jpg');
            background-size: cover ; /* L'image sera contenue dans la fenêtre */
            overflow: hidden;
}

.container {
    display: flex;
    flex-direction: column; /* Garde les éléments en colonne */
    align-items: center; /* Centre le contenu */
    justify-content: center; /* Centre verticalement */
    width: 100%;
    height: 100%;
    margin:30px;

}
.btn-container {
    margin-top: 70px; /* Ajuste la valeur selon ton besoin */
}
.form-container {
    padding: 4px; /* Espace intérieur du formulaire */
    margin-top: 26px; 
    margin-left:-5px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 100%;
    max-width: 400px;
}
.form-container textarea{
        width: 94%;

}

}
    </style>
</head>
<body>

<div class="btn-container">
    <button id="showRendezvous" onclick="showRendezvous()">
    <i class="fas fa-comment"></i> Envoyer un message
    </button>
    <button id="showHistorique" onclick="showHistorique()">
        <i class="fas fa-history"></i> Afficher Historique
    </button>
</div>

<div id="form-message" class="form-container" style="display: none;">
    <form method="post" action="">
        <legend><h2><i class="fas fa-paper-plane"></i> Envoyer un message</h2></legend>
        <select name="medecin_id" required>
            <option value="">Choisissez un médecin</option>
            <?php while ($medecin = $result_medecins->fetch_assoc()): ?>
                <option value="<?php echo $medecin['id']; ?>"><?php echo htmlspecialchars($medecin['nom']); ?></option>
            <?php endwhile; ?>
        </select>
        
        <textarea name="message" rows="5" placeholder="Écrivez votre message ici..." required></textarea>
        
        <button type="submit" name="submit">
          <i class="fas fa-paper-plane"></i> Envoyer le message
        </button>
        <div class="back-button">
    <a href="rendezvous.php"><i class="fas fa-arrow-left"></i> Annuler</a>
</div>
    </form>
</div>

<div id="historique" class="message-history" style="display: none;">
    <h2><i class="fas fa-history"></i> Historique des Messages</h2>
    <?php if ($result_historique->num_rows > 0): ?>
        <?php while ($row = $result_historique->fetch_assoc()): ?>
            <div class="message-item">
                <div class="message-content">
                    <strong>Le <?php echo htmlspecialchars($row['date_envoi']); ?></strong> :<br>
                    Message à <?php echo htmlspecialchars($row['medecin']); ?> :<br>
                    "<?php echo htmlspecialchars($row['message']); ?>"
                    
                    <?php if (!empty($row['repondre'])): ?>
                        <div class="reply">
                            <strong>Réponse :</strong><br>
                            "<?php echo htmlspecialchars($row['repondre']); ?>"
                        </div>
                    <?php endif; ?>
                </div>
                <div class="btn-container">
                    <a href="javascript:void(0);" onclick="toggleForm(<?php echo $row['id']; ?>)" class="btn-edit">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="messagerie.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </div>
                <div id="form-modification-<?php echo $row['id']; ?>" class="form-container" style="display:none;">
                    <h2>Modifier le message</h2>
                    <form method="post" action="messagerie.php?edit=<?php echo $row['id']; ?>">
                        <select name="medecin_id" required>
                            <option value="">Choisissez un médecin</option>
                            <?php
                            // Réinitialiser le pointeur des résultats des médecins
                            $result_medecins->data_seek(0);
                            while ($medecin = $result_medecins->fetch_assoc()): ?>
                                <option value="<?php echo $medecin['id']; ?>" <?php echo ($medecin['id'] == $row['medecin_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($medecin['nom']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <textarea name="message" rows="5" required><?php echo htmlspecialchars($row['message']); ?></textarea>
                        <button type="submit" name="update_message">Mettre à jour</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Aucun message trouvé.</p>
    <?php endif; ?>
</div>

<script>
    function showRendezvous() {
        document.getElementById('form-message').style.display = 'block';
        document.getElementById('historique').style.display = 'none';
    }

    function showHistorique() {
        document.getElementById('form-message').style.display = 'none';
        document.getElementById('historique').style.display = 'block';
    }

    function toggleForm(messageId) {
        var form = document.getElementById('form-modification-' + messageId);
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }
</script>

</body>
</html>
