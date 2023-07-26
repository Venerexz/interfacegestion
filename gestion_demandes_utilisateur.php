<!--
Cette page affiche les demandes d'accès envoyé depuis la page "acces_utilisateur.php" et traités dans "traitement_demande_acces.php". 
Il se connecte à la base de données, récupère les demandes, 
les affiche dans un tableau avec leurs informations de base et les ressources associées, 
et permet à l'utilisateur de valider ou refuser chaque demande en cliquant sur des boutons correspondants,
qui envoyent un input vers la page gestion_status_demande.php.
 -->

 <!DOCTYPE html>
<html>
<head>
  <title>Validation des demandes d'accès</title>
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

  <table class="tableau">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Date de la demande</th>
        <th>Date d'expiration</th>
        <th>Statut</th>
        <th>Ressources</th>
        <th>QR Code</th>
        <th>Action</th>
      </tr>
    </thead>
    <?php
      // Connexion à la base de données
      $server = 'localhost';
      $user = 'root';
      $pw = '';
      $db = 'tribunal';
      $link = mysqli_connect($server, $user, $pw, $db);

      // Récupération des demandes d'accès en ordre décroissant
      $query = "SELECT ID_Demande, Nom, Prenom, Email, Date_demande,Date_expiration, Statut, QR_Code
                FROM Demandes_acces
                ORDER BY Date_demande DESC";
      $result = mysqli_query($link, $query);

      // Affichage des demandes d'accès
      while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '  <td>' . $row['ID_Demande'] . '</td>';
        echo '  <td>' . $row['Nom'] . '</td>';
        echo '  <td>' . $row['Prenom'] . '</td>';
        echo '  <td>' . $row['Email'] . '</td>';
        echo '  <td>' . $row['Date_demande'] . '</td>';
        echo '  <td>' . $row['Date_expiration'] . '</td>';
        echo '  <td>' . $row['Statut'] . '</td>';
      
        // Récupération des ressources associées à la demande d'accès
        $query_ressources = "SELECT Ressources.Nom
        FROM Ressources, Demandes_acces_ressources
        WHERE Demandes_acces_ressources.ID_Demande = " . $row['ID_Demande'] . "
        AND Demandes_acces_ressources.ID_Ressource = Ressources.ID_Ressource";
        $result_ressources = mysqli_query($link, $query_ressources);

        echo '  <td>';
        // Affichage des ressources associées à la demande d'accès
        while ($row_ressources = mysqli_fetch_array($result_ressources)) {
          echo $row_ressources['Nom'] . '<br>';
        }
        echo '  </td>';

        // Affichage du QR Code avec un lien pour le visualiser
        echo '  <td>';
        if (!is_null($row['QR_Code'])) {
          $id_demande = $row['ID_Demande'];
          echo '<a href="visualisation_qr_code.php?id_demande=' . $id_demande . '">Voir le QR Code</a>';
        } else {
          echo '-';
        }
        echo '  </td>';

        echo '  <td>';
        // Affichage des boutons seulement si le statut est "En attente"
        if ($row['Statut'] === "En attente") {
          echo '    <form action="gestion_statut_demande.php" method="post">
          <input type="hidden" name="id_demande" value="' . $row['ID_Demande'] . '">
          <input type="submit" name="accepter" value="Accepter">
          <input type="submit" name="refuser" value="Refuser">
        </form>';
        } else {
          echo '-';
        }
        echo '  </td>';
        echo '</tr>';
      }

    // Fermeture de la connexion à la base de données
    mysqli_close($link);
    ?>
  </table>
</body>
</html>
           


