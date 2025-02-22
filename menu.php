<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabinet de Bonheur</title>
        <link rel="icon" type="image/png" href="lgp.png">
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
      
        /* Styles pour le menu */
        .menu {
    position: fixed;
    top: 0;
    left: -250px; /* Cache le menu par défaut */
    width: 250px;
    height: 100vh;
    background-color: #872341; /* Nouvelle couleur de fond */
    color: white;
    transition: left 0.3s ease;
    z-index: 1000;
    padding-top: 20px;
}

.menu.open {
    left: 0;
}

.menu-items {
    padding: 10px;
}

.menu-item {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.menu-item a {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold; /* Texte en gras */
    transition: color 0.3s ease;
}

.menu-item:hover {
    background: #B39188; /* Nouvelle couleur au survol */
    color:#872341;
}

.menu-item i {
    margin-right: 10px;
    font-size: 18px;
}

.conseil {
    margin-top: 3px;
    font-size: 12px;
    color: #bdc3c7;
    padding-left: 28px;
}

/* Bouton pour ouvrir le menu */
.open-menu-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    background-color: #872341; /* Nouvelle couleur */
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
    background-color: #B39188; /* Nouvelle couleur au survol */
   
}
 
.menu-iteme img {
    max-width: 100%; /* Adaptation à la taille du conteneur */
    height: auto; /* Conserve les proportions */
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
        <div class="menu-header">
           
        </div>
        <div class="menu-items">
             <div class="menu-iteme">
            <img src="bg.png" alt="Logo Hôpital" />
            </div>
                <div class="menu-item">
                <a href="modifier_profil.php">
                    <i class="fas fa-user-edit"></i> Modifier mon profil
                </a>
                <div class="conseil">Assurez-vous que votre profil est à jour.</div>
            </div>
            <div class="menu-item">
                <a href="rendezvous.php">
                    <i class="fas fa-calendar-check"></i> Prendre un rendez-vous
                </a>
                <div class="conseil">Planifiez vos consultations en ligne.</div>
            </div>
            <div class="menu-item">
                <a href="messagerie.php">
                    <i class="fas fa-envelope"></i> Messagerie
                </a>
                <div class="conseil">Communiquez avec votre médecin.</div>
            </div>
            <div class="menu-item">
                <a href="suivi_sante.php">
                    <i class="fas fa-heartbeat"></i> Suivi de santé
                </a>
                <div class="conseil">Suivez vos indicateurs de santé.</div>
            </div>
            <div class="menu-item">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
                <div class="conseil">Déconnectez-vous en toute sécurité.</div>
            </div> <hr>
    
    <p class="copyright">© 2025 Cabinet de Bonheur.</p>
</div>
        </div>
        
    
   
    </div>

    <script>
        // Fonction pour ouvrir/fermer le menu
        function toggleMenu() {
            const menu = document.getElementById('menu');
            const openMenuBtn = document.getElementById('open-menu-btn');

            // Bascule l'état du menu
            menu.classList.toggle('open');

            // Bascule la classe pour déplacer le bouton
            openMenuBtn.classList.toggle('menu-open');
        }
    </script>
</body>
</html>