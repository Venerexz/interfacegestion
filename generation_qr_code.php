<!-- Cette page récupere les donnés entrés dans la page acces_admin.php, les insére dans la base de données
et puis génere et affiche le QR Code. Le Qr code est aussi enrégistrés sous format .png dans le dossier "QR Codes"
et envoyé par mail au destinataire -->
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

<div class="formulaire">
<?php
  // Inclusion de la bibliothèque QR Code
  require_once('phpqrcode/qrlib.php'); 
 
  // Inclusion de la bibliothèque PHPMailer
  use PHPMailer\PHPMailer;
  use PHPMailer\Exception;
  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  // Configuration des paramètres d'envoi d'e-mail
  $mail = new PHPMailer\PHPMailer();
  $mail->isSMTP(); // Utilisation de SMTP pour l'envoi d'e-mail
  $mail->Host = 'smtp-relay.sendinblue.com'; // Adresse du serveur SMTP
  $mail->SMTPAuth = true; // Authentification SMTP activée
  $mail->Username = 'projetsnirtribunal@gmail.com'; // Adresse e-mail utilisée pour l'authentification SMTP
  $mail->Password = 'xsmtpsib-36b774ae7d0070f749f3cd42777d27c990e85cce02b1657c36778753c423c34e-2kFfQabzN9SXcm5x'; // Mot de passe de l'adresse e-mail utilisée pour l'authentification SMTP
  $mail->SMTPSecure = 'tls'; // Type de sécurité SMTP
  $mail->Port = 587; // Port SMTP
  $mail->setFrom('projetsnirtribunal@gmail.com', 'Projet SNIR'); // Adresse e-mail et nom de l'expéditeur
  $mail->isHTML(true); // Format HTML activé

  // Connexion à la base de données
  $server = 'localhost';
  $user = 'root';
  $pw = '';
  $db = 'tribunal';
  $link = mysqli_connect($server, $user, $pw, $db);

  // Récupération des données envoyées par la page HTML
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $ressources = $_POST['ressources'];
  $expiration = !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null;

  // Générer une chaîne aléatoire de 36 caractères (18x2) pour l'attribut Valeur_QR_Code
  $valeur_qr_code = bin2hex(random_bytes(18));

  // Insertion de la demande dans la table Demandes_acces
  $query = "INSERT INTO Demandes_acces (Nom, Prenom, Email, Date_demande, Statut, Date_expiration, Valeur_QR_Code)
  VALUES ('$nom', '$prenom', '$email', NOW(), 'Validé', " . ($expiration !== null ? "'$expiration'" : "NULL") . ", '$valeur_qr_code')";
  $result = mysqli_query($link, $query);

  // Récupération de l'ID de la demande d'accès créée
  $id_demande = mysqli_insert_id($link);

  // Insertion de chaque ressource choisie dans la table Demandes_acces_ressources
  foreach($ressources as $id_ressource) {
    $query = "INSERT INTO Demandes_acces_ressources (ID_Demande, ID_Ressource)
    VALUES ('$id_demande', '$id_ressource')";
    mysqli_query($link, $query);
  }

  // Construction de l'URL qui sera encodée dans le QR code
  $data = "$valeur_qr_code";

  // Génération du QR code
QRcode::png($data, "qr_codes/$nom-$prenom.png");

// Enregistrement du QR code dans la base de données
$qr_code = file_get_contents("qr_codes/$nom-$prenom.png");
$qr_code = mysqli_real_escape_string($link, $qr_code);
$query = "UPDATE Demandes_acces SET QR_Code = '$qr_code' WHERE ID_Demande = $id_demande";
mysqli_query($link, $query);

// Envoi d'un message de confirmation
if (mysqli_affected_rows($link) > 0) {
  $mail->addAddress($email); 
  $mail->CharSet = 'UTF-8';
  $mail->Subject = 'Votre demande d\'accès a été validée';
  $mail->Body = 'Bonjour '.$prenom.',<br><br>Votre demande d\'accès a été validée.<br><br>Vous trouverez ci-joint votre QR Code qui vous permettra d\'acceder aux ressources demandés.<br><br>Cordialement<br><br>Le Tribunal d\'Evry';
  $mail->addAttachment("qr_codes/$nom-$prenom.png");
  $mail->send();
  echo 'Le QR Code a été généré et l\'e-mail de confirmation a été envoyé.';
} else {
  echo 'Erreur lors de la mise à jour de la base de données.';
}
?>
<br><br>
<button class="btn" onclick="window.location.href = 'acces_admin.php'">Retour vers la page précedente</button>
</div>