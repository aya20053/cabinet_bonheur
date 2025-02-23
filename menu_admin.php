<?php
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
        /* Menu latéral */
        .menu {
            position: fixed;
            top: 0;
            left: -250px; /* Caché par défaut */
            width: 250px;
            height: 100vh;
            background-color: #883C65;
            color: white;
            transition: left 0.3s ease;
            z-index: 1000;
            padding-top: 10px;
        }

        .menu.open {
            left: 0; /* Menu ouvert */
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

        /* Bouton d'ouverture du menu */
        .open-menu-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color:  #883C65;
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
            left: 270px; /* Déplace le bouton quand le menu est ouvert */
        }

        .open-menu-btn:hover {
            background-color: #883C65;
        }

        /* Styles pour l'image du logo */
        .menu-iteme img {
            max-width: 100%; /* Adaptation à la taille du conteneur */
            height: auto;
            border-radius: 5px;
        }

        .copyright {
            color: #ffff;
            font-size: 12px;
        }
        /* Positionnement et style du badge de notification */
.notif-badge {
    position: absolute; /* Position absolue pour le placer au bon endroit */
    top: -5px; /* Légèrement au-dessus de l’icône */
    right: -10px; /* Décalage à droite */
    background: white; /* Couleur rouge pour attirer l'attention */
    color: black; /* Texte en blanc */
    font-size: 12px; /* Taille du texte */
    font-weight: bold; /* Texte en gras */
    border-radius: 50%; /* Rond parfait */
    padding: 5px; /* Espacement interne */
    min-width: 20px; /* Largeur minimale */
    height: 20px; /* Hauteur fixe pour garder une forme circulaire */
    display: flex; /* Permet de centrer le texte */
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3); /* Effet d’ombre pour donner du relief */
}

/* Positionner le badge sur le lien */
.menu-item {
    position: relative; /* Nécessaire pour que le badge se positionne par rapport au lien */
}

/* Effet d’animation quand un nouveau badge apparaît */
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


    </style>
</head>
<body>
    <!-- Bouton pour ouvrir le menu -->
    <button class="open-menu-btn" id="open-menu-btn" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Menu à gauche -->
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
        // Fonction pour ouvrir/fermer le menu
        function toggleMenu() {
            const menu = document.getElementById('menu');
            const openMenuBtn = document.getElementById('open-menu-btn');
            menu.classList.toggle('open');
            openMenuBtn.classList.toggle('menu-open');
        }
    </script>

<script>
    function checkNotifications() {
        fetch('get_notifications.php') // Appel AJAX au script PHP
        .then(response => response.json()) // Convertit la réponse en JSON
        .then(data => {
            const notifBadge = document.getElementById('notif-badge'); // Sélectionne le badge
            if (data.count > 0) { 
                notifBadge.textContent = data.count; // Affiche le nombre de comptes à valider
                notifBadge.style.display = 'inline-block'; // Affiche le badge
            } else {
                notifBadge.style.display = 'none'; // Cache le badge s'il n'y a rien
            }
        })
        .catch(error => console.error('Erreur de récupération des notifications:', error));
    }

    // Vérifier les notifications toutes les 5 secondes
    setInterval(checkNotifications, 5000);
    checkNotifications(); // Vérifier immédiatement au chargement
</script>

</body>
</html>