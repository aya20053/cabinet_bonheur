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
</body>
</html>