<?php
    session_start();
    include("../include/connexion.inc.php");
    $error_message = '';
    $_SESSION['refresh_sec'] = "30";
    try {
        if (
            isset($_POST['pseudo']) && 
            isset($_POST['password'])
        ) {
            $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS);
            $mdp = md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
            
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['mdp'] = $mdp;

            $result_utilisateur = $cnx->prepare("SELECT idsenior, pseudo, mdp AS mdp_hash FROM qualite_dev.utilisateur WHERE pseudo = :pseudo");
            $result_utilisateur->bindParam(':pseudo', $pseudo);
            $result_utilisateur->execute();

            $result_administrateur = $cnx->prepare("SELECT idadmin, mdpadmin AS mdp_hash FROM qualite_dev.administrateur WHERE nomadmin = :pseudo");
            $result_administrateur->bindParam(':pseudo', $pseudo);
            $result_administrateur->execute();

            if ($result_utilisateur->rowCount() > 0) {
                $user = $result_utilisateur->fetch(PDO::FETCH_OBJ);
                if ($user->mdp_hash == $mdp) {
                    $_SESSION['idsenior'] = $user->idsenior;
                    $_SESSION['isAdmin'] = false;
                    header("location: accueil.php");
                    exit();
                } else {
                    $error_message = "Mot de passe incorrect.";
                }
            } elseif ($result_administrateur->rowCount() > 0) {
                $admin = $result_administrateur->fetch(PDO::FETCH_OBJ);
                if ($admin->mdp_hash == $mdp) {
                    $_SESSION['idadmin'] = $admin->idadmin;
                    $_SESSION['isAdmin'] = true;
                    header("location: accueil.php");
                    exit();
                } else {
                    $error_message = "Mot de passe incorrect.";
                }
            } else {
                $error_message = "Utilisateur inconnu.";
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
    <title>SilverLove | Sign in</title>
    <link rel="stylesheet" href="../css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
</head>

<body>
    <main class="container">
        <!-- Partie gauche -->
        <div class="partie-gauche">
            <img src="../assets/logo.png" alt="Logo SilverLove" title="Logo SilverLove" />
        </div>
        
        <!-- Partie droite -->
        <div class="partie-droite"> 
            <div class="titre">
                <h1>Heureux de vous revoir</h1>
            </div>
            <form action="login.php" method="post" class="formulaire">
                <div class="input-container">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" placeholder="Pseudo sénior" name="pseudo" id="pseudo" class="input" required />
                </div>
                <br />
                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Mot de passe" name="password" id="password" class="input" required />
                </div>
                <br />
                <!-- Rajouter message d'erreur "Erreur de connexion" soit utilisateur inconnu, soit mdp faux -->
                <div class="error-message">
                    <?php 
                        if (!empty($error_message)) {
                            echo "<p>$error_message</p>";
                        }
                    ?>
                </div>
                <input type="submit" value="Se connecter" class="input-submit" /><br />
                <a href="signup.php">Création de compte</a>
            </form>
        </div>
    </main>
</body>
</html>