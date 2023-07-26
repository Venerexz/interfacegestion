<?php
  // Connexion à la base de données
  $server = 'localhost';
  $user = 'root';
  $pw = '';
  $db = 'tribunal';
  $link = mysqli_connect($server, $user, $pw, $db);



  // Récupération des informations sur les ressources
  $query = "SELECT * FROM Ressources";
  $result = mysqli_query($link, $query);
?>

<link rel="stylesheet" href="style.css">

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

<table class="tableau2">
  <thead>
    <tr>
      <th>ID Ressource</th>
      <th>Nom</th>
      <th>Type</th>
      <th>Sensibilité</th>
      <th>Capacité totale</th>
      <th>Capacité actuelle</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_array($result)): ?>
      <tr>
        <td><?php echo $row['ID_Ressource']; ?></td>
        <td><?php echo $row['Nom']; ?></td>
        <td><?php echo $row['Type_ressource']; ?></td>
        <td><?php echo $row['Sensibilite']; ?></td>
        <td><?php echo $row['Capacite_totale']; ?></td>
        <td><?php echo $row['Capacite_actuelle']; ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
