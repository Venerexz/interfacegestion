<!DOCTYPE html>
<html>
<head>
    <title>Gérer l'accès aux ressources</title>
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

    // Récupération de l'ID du visage sélectionné dans la page précédente
    $id_visage = $_GET["id"];

    // Récupération du nom du visage
    $sql_nom_visage = "SELECT Nom FROM Visages WHERE ID_Visage = ".$id_visage;
    $result_nom_visage = $conn->query($sql_nom_visage);
    $row_nom_visage = $result_nom_visage->fetch_assoc();
    $nom_visage = $row_nom_visage["Nom"];

    // Récupération de toutes les ressources disponibles
    $sql_ressources = "SELECT * FROM Ressources";
    $result_ressources = $conn->query($sql_ressources);

    // Récupération des ressources auxquelles le visage a déjà accès
    $sql_acces_visage = "SELECT ID_Ressource FROM Visages_acces_ressources WHERE ID_Visage = ".$id_visage;
    $result_acces_visage = $conn->query($sql_acces_visage);
    $acces_visage = array();
    if ($result_acces_visage->num_rows > 0) {
        while($row_acces_visage = $result_acces_visage->fetch_assoc()) {
            array_push($acces_visage, $row_acces_visage["ID_Ressource"]);
        }
    }

    // Fermeture de la connexion
    $conn->close();
?>

<div class="formulaire">
<form method="post" action="enregistrer_acces_visages.php">
    <?php
        // Affichage de toutes les ressources sous forme de checkbox, en cochant les cases pour les ressources auxquelles le visage a déjà accès
        if ($result_ressources->num_rows > 0) {
            while($row_ressources = $result_ressources->fetch_assoc()) {
                $checked = in_array($row_ressources["ID_Ressource"], $acces_visage);
                echo '<input type="checkbox" name="ressources[]" value="'.$row_ressources["ID_Ressource"].'"';
                if ($checked) echo ' checked="checked"';
                echo ' /> '.$row_ressources["Nom"].'<br />';
            }
        } else {
            echo "Aucune ressource disponible.";
        }
    ?>
    <input type="hidden" name="id_visage" value="<?php echo $id_visage; ?>" /><br>
    <input class="generer" type="submit" value="Valider" />
                    
</form>
</div>
</body>
</html>
