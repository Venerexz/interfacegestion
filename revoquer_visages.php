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

// Récupération de l'action à effectuer (révoquer ou redonner l'accès)
if (isset($_GET["action"]) && ($_GET["action"] == "revoquer" || $_GET["action"] == "redonner")) {
    $action = $_GET["action"];
} else {
    die("Action invalide.");
}

// Récupération de l'ID du visage à modifier
$id_visage = $_GET["id"];

// Vérification que l'ID est valide
$sql = "SELECT * FROM Visages WHERE ID_Visage = ".$id_visage;
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("ID de visage invalide.");
}

// Mise à jour du statut du visage
if ($action == "revoquer") {
    $sql_update = "UPDATE Visages SET Statut = 'Révoqué' WHERE ID_Visage = ".$id_visage;
} else {
    $sql_update = "UPDATE Visages SET Statut = 'Validé' WHERE ID_Visage = ".$id_visage;
}
if (!$conn->query($sql_update)) {
    die("Erreur lors de la mise à jour du statut du visage: " . $conn->error);
}

// Redirection vers la page de liste des visages
header("Location: liste_visages.php");
exit;
?>
