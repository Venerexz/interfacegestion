<!-- Cette page traite les info réçues de l'appuye des boutons "Valider" et "Refuser" sur la page
gestion_demandes_utilisateurs.php; Si le bouton "Valider" est cliqué, un QR Code est géneré, enrégistré et affiché,
exactement comme pour la page "generatoin_qr_code.php", puis le statut de la demande change en "Validé" et envoye un mail.
Sinon, si "Refuser" est appuyé, la demande change le statut en "Refusé" -->

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
  
  // Vérification de la soumission du formulaire
  if (isset($_POST['id_demande']) && isset($_POST['accepter']) || isset($_POST['refuser'])) {
    $id_demande = mysqli_real_escape_string($link, $_POST['id_demande']);
    
    // Détermination du nouveau statut en fonction de la soumission du formulaire
    if (isset($_POST['accepter'])) {
      $nouveau_statut = 'Validé'; 

      // Récupération des informations sur la demande d'accès sélectionnée
      $query = "SELECT Nom, Prenom, Email, Valeur_QR_Code FROM Demandes_acces WHERE ID_Demande = ?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $id_demande);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_array($result);
      $nom = $row['Nom'];
      $prenom = $row['Prenom'];
      $email = $row['Email'];
      $valeur_qr_code = $row['Valeur_QR_Code'];

      // Construction de l'URL qui sera encodée dans le QR code
      $data = "$valeur_qr_code";
    
      // Generation of the QR code
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

    } elseif (isset($_POST['refuser'])) {
        $nouveau_statut = 'Refusé';
        header('Location: gestion_demandes_utilisateur.php');
      }
      
    // Mise à jour du statut de la demande d'accès
    $query = "UPDATE Demandes_acces SET Statut = '$nouveau_statut' WHERE ID_Demande = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_demande);
    mysqli_stmt_execute($stmt);
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($link);
  ?>
  <br><br>
  <button class="btn" onclick="window.location.href = 'gestion_demandes_utilisateur.php'">Retour vers la page précedente</button>
  </div>
  
