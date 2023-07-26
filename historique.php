<?php
  // Connexion à la base de données
  $server = 'localhost';
  $user = 'root';
  $pw = '';
  $db = 'tribunal';
  $link = mysqli_connect($server, $user, $pw, $db);



    // Requête SQL pour récupérer les données de la table Historique_acces avec les noms correspondants
    $query = "SELECT ID_Acces, da.Nom AS NomDemandeur, da.Prenom AS PrenomDemandeur, v.Nom AS NomVisage, r.Nom AS NomRessource, ha.Date_acces
            FROM Historique_acces ha
            LEFT JOIN Demandes_acces da ON ha.ID_Demande = da.ID_Demande
            LEFT JOIN Visages v ON ha.ID_Visage = v.ID_Visage
            JOIN Ressources r ON ha.ID_Ressource = r.ID_Ressource;
            ";
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
      <th>ID_Acces</th>
      <th>Nom Demande</th>
      <th>Nom Visage</th>
      <th>Ressource</th>
      <th>Date accés</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_array($result)): ?>
      <tr>
        <td><?php echo $row['ID_Acces']; ?></td>
        <td><?php echo $row['NomDemandeur'] . ' ' . $row['PrenomDemandeur']; ?></td>
        <td><?php echo $row['NomVisage']; ?></td>
        <td><?php echo $row['NomRessource']; ?></td>
        <td><?php echo $row['Date_acces']; ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>