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

// Récupération de l'ID de l'image
$id = $_GET["id"];

// Récupération de l'image à partir de la base de données
$sql = "SELECT * FROM Visages WHERE ID_Visage = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["Encodage"];
    header("Content-type: image");
    echo $image;

} else {
    echo "Image non trouvée";
}

$conn->close();
?>

