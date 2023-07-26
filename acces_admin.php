<!-- La page comprend un formulaire permettant aux utilisateurs de saisir des 
informations telles que le nom, le prénom, l'e-mail et la date d'expiration. 
Il y a également des cases à cocher pour les ressources.

La page utilise également une connexion à une base de données phpmyadmin pour récupérer 
les noms des ressources et les afficher sous forme de cases à cocher. 
Le bouton "Générer QR Code" permet de soumettre les informations saisies et de les envoyer à 
une autre page nommée "generation_qr_code.php" -->

<!DOCTYPE html>
<html>
<head>
  <title>Génération des codes d'accès admin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navigation">
            <nav>
                <a href="./home.html"><h2 class="logo">Tribunal <span>d'Evry</span></h2></a>
                  <ul>
                    <li><a href="./acces_admin.php">Generer un QR Code</a></li>
                    <li><a href="./gestion_demandes_utilisateur.php">Gestion des demandes</a></li>
                    <li><a href="./liste_visages.php">Gestion Visages</a></li>
                    <li><a href="./historique.php">Historique accés</a></li>
                    <li><a href="./etat_occupation_ressources.php">Ressources</a></li>
                    <li><a href="./acces_serrures.html">Accés aux serrures</a></li>
                    </ul>
            </nav>
        </div>
        <br>
  <div class="formulaire">
    <h1>Génération des codes d'accès</h1>
    <form action="generation_qr_code.php" method="post">
      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom" required placeholder="Entrez votre nom"><br><br>
      <label for="prenom">Prénom :</label>
      <input type="text" id="prenom" name="prenom" required required placeholder="Entrez votre prenom"><br><br>
      <label for="email">Email :</label>
      <input type="email" id="email" name="email" placeholder="Entrez votre email"><br><br>
      <label for="date_expiration">Date d'expiration :</label>
      <input type="datetime-local" id="date_expiration" name="date_expiration"><br><br>
      <label for="ressources">Ressources :</label><br><br>
  <?php
    // Connexion à la base de données
    $server = 'localhost';
    $user = 'root';
    $pw = '';
    $db = 'tribunal';
    $link = mysqli_connect($server, $user, $pw, $db);

    // Récupération des ressources
    $query = "SELECT ID_Ressource, Nom FROM Ressources";
    $result = mysqli_query($link, $query);

    // Affichage des cases à cocher pour chaque ressource
    while ($row = mysqli_fetch_array($result)) {
      echo '<input type="checkbox" id="' . $row['ID_Ressource'] . '" name="ressources[]" value="' . $row['ID_Ressource'] . '">';
      echo '<label for="' . $row['ID_Ressource'] . '">' . $row['Nom'] . '</label><br>';
    }
  ?>
<br><br>
<input class="generer" type="submit" value="Générer QR Code">
  </div>
    </form>
</body>
</html>
