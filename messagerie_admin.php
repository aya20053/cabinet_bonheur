<?php
session_start();
ob_start();

include 'menu_admin.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirection vers la page de connexion
    exit();
}

// Connexion à la base de données
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("La connexion a échoué: " . $e->getMessage());
}

// Récupération des messages
$query = "
    SELECT m.*, f.prenom AS femme_prenom, f.nom AS femme_nom, d.prenom AS medecin_prenom, d.nom AS medecin_nom
    FROM messages m
    LEFT JOIN femmes_enceintes f ON m.femme_id = f.id
    LEFT JOIN medecins d ON m.medecin_id = d.id
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll();

// Recherche des messages
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $query = "
        SELECT m.*, f.prenom AS femme_prenom, f.nom AS femme_nom, d.prenom AS medecin_prenom, d.nom AS medecin_nom
        FROM messages m
        LEFT JOIN femmes_enceintes f ON m.femme_id = f.id
        LEFT JOIN medecins d ON m.medecin_id = d.id
        WHERE m.message LIKE :search OR f.nom LIKE :search OR d.nom LIKE :search
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
    $messages = $stmt->fetchAll();
}

// Vérifier si un formulaire de marquage a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['marquer_lu'])) {
    // Mettre à jour le statut du message à "lu"
    $message_id = $_POST['message_id'];
    $update_query = "UPDATE messages SET statut = 'lu' WHERE id = :message_id";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute(['message_id' => $message_id]);

    // Rediriger pour afficher la mise à jour
    header("Location: messagerie_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #3B1C32;
            margin-bottom: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 25px;
            padding: 10px 15px;
            background: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin-left: auto;
            margin-right: auto; /* Centrer la barre de recherche */
        }
        .search-bar i {
            font-size: 18px;
            color: #872341;
            padding: 8px;
        }
        .search-bar input {
            padding: 10px;
            font-size: 16px;
            border: none;
            outline: none;
            background: transparent;
            width: 100%; /* Prendre tout l'espace disponible */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #3B1C32;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        .reply {
            background: #F4CCE9;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
            border-left: 5px solid #A64D79;
        }
        .btn {
            padding: 7px;
            border: solid 1px #3B1C32;
            color: #3B1C32;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
            Margin: 25px;
            background: #F4CCE9;

        }
        .btn:hover {
            background-color: #3B1C32;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-envelope"></i> Messagerie</h1>

        <!-- Barre de recherche -->
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <form method="get" action="" style="width: 100%;">
                <input type="text" name="search" placeholder="Rechercher des messages..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
            </form>
        </div>

        <!-- Tableau des messages -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Message</th>
                    <th>Date d'envoi</th>
                    <th>Statut</th>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['id']) ?></td>
                        <td><?= htmlspecialchars($msg['message']) ?></td>
                        <td><?= htmlspecialchars($msg['date_envoi']) ?></td>
                        <td><?= htmlspecialchars($msg['statut']) ?></td>
                        <td><?= htmlspecialchars($msg['femme_prenom'] . ' ' . $msg['femme_nom']) ?></td>
                        <td><?= htmlspecialchars($msg['medecin_prenom'] . ' ' . $msg['medecin_nom']) ?></td>
                        <td>
                            <a href="#" class="btn" onclick="showReplyForm(<?= $msg['id'] ?>, '<?= htmlspecialchars($msg['femme_id']) ?>')">Répondre</a>
                            <div id="reply-form-<?= $msg['id'] ?>"></div>
                            <?php if ($msg['statut'] === 'non lu'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                                    <button type="submit" name="marquer_lu" class="btn">Marquer comme lu</button>
                                </form>
                            <?php else: ?>
                                <span class="badge-lu">✔️ Lu</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function showReplyForm(messageId, femmeId) {
            const replyForm = `
                <div>
                    <h3>Répondre au patient</h3>
                    <form method="POST" action="send_reply.php">
                        <input type="hidden" name="femme_id" value="${femmeId}">
                        <input type="hidden" name="message_id" value="${messageId}">
                        <textarea name="reply" rows="4" required placeholder="Écrivez votre réponse ici..."></textarea>
                        <button type="submit">Envoyer</button>
                        <button type="button" onclick="document.getElementById('reply-form-${messageId}').innerHTML = ''">Annuler</button>
                    </form>
                </div>
            `;
            document.getElementById(`reply-form-${messageId}`).innerHTML = replyForm;
        }
    </script>

    <script>
        function checkNotifications() {
            fetch("update_message_status.php")
                .then(response => response.json())
                .then(data => {
                    if (data.new_messages > 0) {
                        document.getElementById("notification").style.display = "block";
                    }
                })
                .catch(error => console.error("Erreur:", error));
        }

        setInterval(checkNotifications, 5000); // Vérifie toutes les 5 secondes
    </script>

</body>
</html>