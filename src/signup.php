<?php
  session_start();
  include("../include/connexion.inc.php");
  $error_message = '';
  $_SESSION['refresh_sec'] = "30";

  try {
    if (
        isset($_POST['nom']) && 
        isset($_POST['prenom']) && 
        isset($_POST['password']) && 
        isset($_POST['dateNaissance']) && 
        isset($_POST['pseudo']) && 
        isset($_POST['statut']) && 
        isset($_POST['tel'])
    ) {
      $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
      $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
      $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS);
      $mdp = md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
      $dateNaissance = filter_input(INPUT_POST, 'dateNaissance', FILTER_SANITIZE_SPECIAL_CHARS);
      $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_SPECIAL_CHARS);
      $avatar = filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_SPECIAL_CHARS);
      $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_SPECIAL_CHARS);

      if (empty($avatar)) {
        $avatar = NULL;
    }

      $_SESSION['pseudo'] = $pseudo;
      $_SESSION['mdp'] = $mdp;

      $req_utilisateur = "
      INSERT INTO qualite_dev.utilisateur 
      (nom, prenom, mdp, datenaissance, pseudo, statut, avatar, tel, idadmin) 
      VALUES 
      (:nom, :prenom, :mdp, :datenaissance, :pseudo, :statut, :avatar, :tel, 'admin');
      ";

      $stmt = $cnx->prepare($req_utilisateur);

      $stmt->bindParam(':nom', $nom);
      $stmt->bindParam(':prenom', $prenom);
      $stmt->bindParam(':pseudo', $pseudo);
      $stmt->bindParam(':mdp', $mdp);
      $stmt->bindParam(':datenaissance', $dateNaissance);
      $stmt->bindParam(':statut', $statut);
      $stmt->bindParam(':avatar', $avatar);
      $stmt->bindParam(':tel', $tel);

      $test = $cnx->query("SELECT * FROM qualite_dev.utilisateur WHERE pseudo = '$pseudo'");
      if ($test->rowCount() == 0) {
        if ($stmt->execute()) {
          $idsenior = $cnx->lastInsertId();
          $_SESSION['idsenior'] = $idsenior;
          $_SESSION['isAdmin'] = false;
          header("location: accueil.php");
          exit();
        } else {
            $error_message = "Erreur lors de l'inscription.";
        }
      } else {
        $error_message = "Pseudo déjà utilisé.";
      }
    }
  } catch (PDOException $e) {
      echo "Erreur : " . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SilverLove | Sign up</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/signup.css" />
</head>
  <body>
    <main class="container">
      <!-- Partie gauche -->
      <div class="partie-gauche">
        <img
          src="../assets/logo.png"
          alt="Logo SilverLove"
          title="Logo SilverLove"
        />
      </div>

      <!-- Partie droite -->
      <div class="partie-droite">
        <div class="titre">
          <h1>Bienvenue</h1>
        </div>

        <form action="signup.php" method="post" class="formulaire">
          <div class="two-inputs">
            <input type="text" name="nom" placeholder="Nom" required />
            <input type="text" name="prenom" placeholder="Prenom" required/>
          </div>

          <input type="password" name="password" placeholder="Mot de passe" required />
          <input type="date" name="dateNaissance" placeholder="Date de Naissance" required/>

          <div class="two-inputs">
            <input type="text" name="pseudo" placeholder="Pseudo" required/>
            <select name="statut" required>
              <option value="" disabled selected>Statut</option>
              <option value="Absent">Absent</option>
              <option value="Ne pas déranger">Ne pas déranger</option>
              <option value="En ligne">En ligne</option>
            </select>
          </div>

          <div class="avatar-options">
            <label>
              <input type="radio" name="avatar" value="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar1.jpg">
              <img src="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar1.jpg" alt="Avatar 1">
            </label>
            <label>
              <input type="radio" name="avatar" value="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar2.jpg">
              <img src="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar2.jpg" alt="Avatar 2">
            </label>
            <label>
              <input type="radio" name="avatar" value="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar3.jpg">
              <img src="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar3.jpg" alt="Avatar 3">
            </label>
            <label>
              <input type="radio" name="avatar" value="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar4.jpg">
              <img src="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar4.jpg" alt="Avatar 4">
            </label>
          </div>

          <input type="text" name="tel" placeholder="Téléphone" required pattern="[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]"/>
          <div class="error-message">
            <?php 
                if (!empty($error_message)) {
                    echo "<p>$error_message</p>";
                }
            ?>
          </div>
          <input type="submit" name="submit" value="S'inscrire" class="input-submit">
        </form>
      </div>
    </main>
  </body>
</html>
