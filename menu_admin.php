<?php
// Démarrer la session et activer la sortie tampon

// Vérifier si une session est déjà active avant de l'initialiser
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

try {
    // Connexion à la base de données avec PDO
    $dsn = "mysql:host=localhost;dbname=clinique_bonheur;charset=utf8mb4";
    $user = "root";
    $pass = "";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les erreurs SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération des résultats
        PDO::ATTR_EMULATE_PREPARES => false // Désactive l'émulation des requêtes préparées
    ]);

} catch (PDOException $e) {
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

// Récupération du nombre de messages non lus
$reqNotif = $pdo->query("SELECT COUNT(*) AS total FROM messages WHERE statut = 'non lu'");
$notif = $reqNotif->fetch();
$totalMessagesNonLus = $notif['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabinet de Bonheur</title>
    <link rel="icon" type="image/png" href="lgp.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles généraux */
        .menu {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background-color: #883C65;
            color: white;
            transition: left 0.3s ease;
            z-index: 1000;
            padding-top: 10px;
        }

        .menu.open {
            left: 0;
        }

        .menu-items {
            padding: 10px;
        }

        .menu-item {
            padding: 8px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(225, 117, 100, 0.2);
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .open-menu-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #883C65;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            transition: left 0.3s ease, background 0.3s ease;
            border-radius: 5px;
        }

        .open-menu-btn.menu-open {
            left: 270px;
        }

        .open-menu-btn:hover {
            background-color: #883C65;
        }

        .menu-iteme img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .copyright {
            color: #ffff;
            font-size: 12px;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: white;
            color: black;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            padding: 5px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .menu-item {
            position: relative;
        }

        @keyframes notifBounce {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

        .notif-badge.animated {
            animation: notifBounce 0.5s ease-in-out;
        }

        .badge {
            background-color: red;
            color: white;
            font-size: 12px;
            padding: 5px 8px;
            border-radius: 50%;
            position: absolute;
            top: 0;
            right: 0;
        }
        .notification-badge {
        background-color:WHITE;
        color: BLACK;
        font-size: 14px;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 50%;
        position: absolute;
        top: -5px;
        right: -5px;
        display: inline-block;
        min-width: 20px;
        text-align: center;
    }
    </style>
</head>
<body>
    <button class="open-menu-btn" id="open-menu-btn" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="menu" id="menu">
        <div class="menu-items">
            <div class="menu-iteme">
                <img src="bg.png" alt="Logo Hôpital" />
            </div>
            <div class="menu-item">
                <a href="patients.php">
                    <i class="fas fa-users"></i> Gestion des Patients
                </a>
            </div>
            <div class="menu-item">
                <a href="demandes_validation.php">
                    <i class="fas fa-user-check"></i> Demandes de Validation 
                    <span id="notif-badge" class="notif-badge" style="display: none;">0</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="gestion_medecins.php">
                    <i class="fas fa-user-md"></i> Gestion des Médecins
                </a>
            </div>
            <div class="menu-item">
                <a href="rendezvous_admin.php">
                    <i class="fas fa-calendar-alt"></i> Rendez-vous
                </a>
            </div>
            <div class="menu-item">
    <a href="messagerie_admin.php">
        <i class="fas fa-envelope"></i> Messages
        <?php
        // Vérifier s'il y a des messages non lus
        $query = "SELECT COUNT(*) AS new_messages FROM messages WHERE statut = 'non lu'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        $new_messages = $result['new_messages'];
        
        if ($new_messages > 0) {
            echo "<span class='notification-badge'>$new_messages</span>";
        }
        ?>
    </a>
</div>

            <div class="menu-item">
                <a href="notes_medicales.php">
                    <i class="fas fa-file-medical"></i> Notes Médicales
                </a>
            </div>
            <div class="menu-item">
                <a href="statistiques.php">
                    <i class="fas fa-chart-line"></i> Statistiques
                </a>
            </div>
            <div class="menu-item">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
            <hr>
            <p class="copyright">© 2025 Cabinet de Bonheur.</p>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            const openMenuBtn = document.getElementById('open-menu-btn');
            menu.classList.toggle('open');
            openMenuBtn.classList.toggle('menu-open');
        }

        function checkNotifications() {
            fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                const notifBadge = document.getElementById('notif-badge');
                if (data.count > 0) {
                    notifBadge.textContent = data.count;
                    notifBadge.style.display = 'inline-block';
                } else {
                    notifBadge.style.display = 'none';
                }
            })
            .catch(error => console.error('Erreur de récupération des notifications:', error));
        }

        setInterval(checkNotifications, 5000);
        checkNotifications();
    </script>

</body>
</html>
