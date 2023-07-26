<!DOCTYPE html>
<html>
<head>
    <title>Gestion Reconnaissance Faciale</title>
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
    <table class="tableau2">
        <thead>
            <tr>
                <th>ID Visage</th>
                <th>Nom</th>
                <th>Statut</th>
                <th>Date insertion</th>
                <th>Image</th>
                <th>Gestion de l'accés</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "tribunal";
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Vérification de la connexion
            if ($conn->connect_error) {
                die("Erreur de connexion à la base de données: " . $conn->connect_error);
            }

            // Récupération des données de la table Visages
            $sql = "SELECT * FROM Visages";
            $result = $conn->query($sql);

            // Affichage des données sous forme de tableau
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_Visage"] . "</td>";
                    echo "<td>" . $row["Nom"] . "</td>";
                    echo "<td>" . $row["Statut"] . "</td>";
                    echo "<td>" . $row["Date_insertion"] . "</td>";
                    echo "<td><a href=\"afficher_visage.php?id=" . $row["ID_Visage"] . "\">Afficher l'image</a></td>";
                    echo "<td><a href=\"gerer_acces_visages.php?id=" . $row["ID_Visage"] . "\">Gérer l'accès</a></td>";
                    if($row["Statut"] == "Validé"){
                        echo "<td><a href=\"revoquer_visages.php?id=" . $row["ID_Visage"] . "&action=revoquer\"><button class=\"revoquer-button\">Révoquer l'accès</button></a></td>";
                      }
                      elseif($row["Statut"] == "Révoqué"){
                        echo "<td><a href=\"revoquer_visages.php?id=" . $row["ID_Visage"] . "&action=redonner\"><button class=\"redonner-button\">Redonner l'accès</button></a></td>";
                      }
                      else{
                        echo "<td></td>";
                      }                                       
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan=\"4\">Aucun résultat</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
