<!-- Cette page récupere les donnés entrés dans la page acces_utilisateur.php,
les insére dans la base de données et puis affiche un message de confirmation -->

<link rel="stylesheet" href="style.css">

<div class="navigation">
      <nav>
          <a href="./acces_utilisateur.php"><h2 class="logo">Tribunal <span>d'Evry</span></h2></a>
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

  // Récupération des données envoyées par la page HTML
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $ressources = $_POST['ressources'];
  $expiration = !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null;

  // Générer une chaîne aléatoire de 36 caractères pour l'attribut Valeur_QR_Code
  $valeur_qr_code = bin2hex(random_bytes(36));

  // Insertion de la demande dans la table Demandes_acces
  $query = "INSERT INTO Demandes_acces (Nom, Prenom, Email, Date_demande, Date_expiration, Valeur_QR_Code)
  VALUES ('$nom', '$prenom', '$email', NOW(), " . ($expiration !== null ? "'$expiration'" : "NULL") . ", '$valeur_qr_code')";
  $result = mysqli_query($link, $query);

  // Récupération de l'ID de la demande d'accès créée
  $id_demande_acces = mysqli_insert_id($link);

  // Insertionde chaque ressource choisie dans la table Demandes_acces_ressources
  foreach($ressources as $id_ressource) {
    $query = "INSERT INTO Demandes_acces_ressources (ID_Demande, ID_Ressource)
              VALUES ('$id_demande_acces', '$id_ressource')";
    mysqli_query($link, $query);
  }

  // Affichage d'un message de confirmation
  if ($result) {
    echo "Votre demande a bien été envoyée.";
  } else {
    echo "Une erreur est survenue, veuillez réessayer plus tard.";
  }

  // Fermeture de la connexion à la base de données
  mysqli_close($link);
?>
</div>