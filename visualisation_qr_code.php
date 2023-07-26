<!DOCTYPE html>
<html>
<head>
	<title>Visualisation du QR Code</title>
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
  <div class="formulaire">
	<?php
		// Connexion à la base de données
		$server = 'localhost';
		$user = 'root';
		$pw = '';
		$db = 'tribunal';
		$link = mysqli_connect($server, $user, $pw, $db);

		// Récupération de l'ID de la demande d'accès
		$id_demande = $_GET['id_demande'];

		// Récupération du QR Code de la demande d'accès
		$query = "SELECT QR_Code FROM Demandes_acces WHERE ID_Demande = $id_demande";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_assoc($result);
		$qr_code = $row['QR_Code'];

		// Affichage du QR Code
		if (!is_null($qr_code)) {
			echo '<img src="data:image/jpeg;base64,' . base64_encode($qr_code) . '" />';
		} else {
			echo 'Aucun QR Code disponible';
		}

		mysqli_close($link);
	?>
	<br><br>
	<button class="btn" onclick="window.location.href = 'gestion_demandes_utilisateur.php'">Retour vers la page précedente</button>
	</div>
</body>
</html>

