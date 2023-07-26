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

// Vérification que le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de l'ID du visage sélectionné
    $id_visage = $_POST["id_visage"];

    // Suppression de toutes les entrées dans la table Visages_acces_ressources pour ce visage
    $sql_delete = "DELETE FROM Visages_acces_ressources WHERE ID_Visage = ".$id_visage;
    if (!$conn->query($sql_delete)) {
        die("Erreur lors de la suppression des accès existants: " . $conn->error);
    }

    // Ajout des nouvelles entrées dans la table Visages_acces_ressources
    $ressources = $_POST["ressources"];
    foreach ($ressources as $id_ressource) {
        $sql_insert = "INSERT INTO Visages_acces_ressources (ID_Visage, ID_Ressource) VALUES (".$id_visage.", ".$id_ressource.")";
        if (!$conn->query($sql_insert)) {
            die("Erreur lors de l'ajout d'une demande d'accès à la ressource ".$id_ressource.": " . $conn->error);
        }
    }

    // Redirection vers la page précédente
    header("Location: liste_visages.php?id=".$id_visage);
    exit();
}

// Fermeture de la connexion
$conn->close();
?>
