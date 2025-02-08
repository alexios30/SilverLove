<?php
session_start();
include("../include/connexion.inc.php");
include("../include/header.inc.php");
$page = $_SERVER['PHP_SELF'];
$sec = $_SESSION['refresh_sec'];

if (!isset($_SESSION['pseudo']) || !isset($_SESSION['mdp'])) {
    header("location: login.php");
    exit();
}

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (
            isset($_POST['nom']) && 
            isset($_POST['prenom']) && 
            isset($_POST['dateNaissance']) && 
            isset($_POST['pseudo']) && 
            isset($_POST['statut']) && 
            isset($_POST['avatar']) &&
            isset($_POST['tel'])
        ) {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
            $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS);
            $dateNaissance = filter_input(INPUT_POST, 'dateNaissance', FILTER_SANITIZE_SPECIAL_CHARS);
            $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_SPECIAL_CHARS);
            $avatar = filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_SPECIAL_CHARS);
            $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_SPECIAL_CHARS);
            $idsenior = $_SESSION['idsenior'];

            if(!empty($_POST['ancienmotdepasse'])){
                $ancienmdpHash = md5(filter_input(INPUT_POST, 'ancienmotdepasse', FILTER_SANITIZE_SPECIAL_CHARS));
                $mdpSession = $_SESSION['mdp'];
                if ($mdpSession === $ancienmdpHash) {
                    if(isset($_POST['nouveaumotdepasse']) && isset($_POST['confirmationmotdepasse'])){
                        $nouveaumdp = md5(filter_input(INPUT_POST, 'nouveaumotdepasse', FILTER_SANITIZE_SPECIAL_CHARS));
                        $confirmationmdp = md5(filter_input(INPUT_POST, 'confirmationmotdepasse', FILTER_SANITIZE_SPECIAL_CHARS));
                        if($nouveaumdp === $confirmationmdp){
                            $requete = $cnx->prepare("UPDATE qualite_dev.utilisateur SET mdp = :confirmationmdp WHERE idsenior = :idsenior");
                            $requete->bindParam(':confirmationmdp', $confirmationmdp);
                            $requete->bindParam(':idsenior', $idsenior);
                            $requete->execute();
                        } else {
                            $errorMessages['confirmationmotdepasse'] = "Les nouveaux mots de passe ne correspondent pas.";
                        }
                    } else {
                        $errorMessages['nouveaumotdepasse'] = "Vous n'avez pas complété les nouveaux mots de passe.";
                    }
                } else {
                    $errorMessages['ancienmotdepasse'] = "L'ancien mot de passe est incorrect.";
                }
            }

            $requete = $cnx->prepare("UPDATE qualite_dev.utilisateur SET nom = :nom, prenom = :prenom, datenaissance = :dateNaissance, avatar = :avatar, statut = :statut, tel = :tel WHERE idsenior = :idsenior");
            $requete->bindParam(':nom', $nom);
            $requete->bindParam(':prenom', $prenom);
            $requete->bindParam(':dateNaissance', $dateNaissance);
            $requete->bindParam(':statut', $statut);
            $requete->bindParam(':avatar', $avatar);
            $requete->bindParam(':idsenior', $idsenior);
            $requete->bindParam(':tel', $tel);
            $requete->execute();

            if ($pseudo !== $_SESSION['pseudo']) {
                $test = $cnx->query("SELECT pseudo FROM qualite_dev.utilisateur WHERE pseudo = '$pseudo'");
                if ($test->rowCount() == 0) {
                    $requete = $cnx->prepare("UPDATE qualite_dev.utilisateur SET pseudo = :pseudo WHERE idsenior = :idsenior");
                    $requete->bindParam(':pseudo', $pseudo);
                    $requete->bindParam(':idsenior', $idsenior);
                    $requete->execute();
                    $_SESSION['pseudo'] = $pseudo;
                } else {
                    $errorMessages['pseudo'] = "Le pseudo est déjà pris.";
                }
            }
        }
    } catch (PDOException $e) {
        $errorMessages['general'] = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <title>SilverLove | Profil</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/account.css" />
</head>
<body>
<main>
    <form action="account.php" method="post" class="formulaire">
        <?php
        if (isset($_SESSION['pseudo'])) {
            $pseudo = $_SESSION['pseudo'];
            $stmt = $cnx->prepare("SELECT * FROM qualite_dev.utilisateur WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
                $nom = $ligne['nom'];
                $prenom = $ligne['prenom'];
                $dateNaissance = $ligne['datenaissance'];
                $avatar = $ligne['avatar'];
                $tel = $ligne['tel'];
                $pseudo = $ligne['pseudo'];
                $statut = $ligne['statut'];
                $avatars = [
                    "https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar1.jpg",
                    "https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar2.jpg",
                    "https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar3.jpg",
                    "https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar4.jpg"
                ];
        ?>

        <div class="form-group">
            <label for="Avatar">Choisissez votre avatar</label>
            <div class="avatar-options">
                <?php foreach ($avatars as $avatarOption): ?>
                    <label>
                        <input type="radio" name="avatar" value="<?= $avatarOption ?>" <?= $avatarOption == $avatar ? 'checked' : '' ?>>
                        <img src="<?= $avatarOption ?>" alt="Avatar">
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="two-inputs">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= $nom ?>" placeholder="Nom" required />
                <?php if (isset($errorMessages['nom'])): ?>
                    <span class="error-message"><?= $errorMessages['nom'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= $prenom ?>" placeholder="Prénom" required />
                <?php if (isset($errorMessages['prenom'])): ?>
                    <span class="error-message"><?= $errorMessages['prenom'] ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="two-inputs">
            <div class="form-group">
                <label for="dateNaissance">Date de Naissance</label>
                <input type="date" id="dateNaissance" name="dateNaissance" value="<?= $dateNaissance ?>" required />
                <?php if (isset($errorMessages['dateNaissance'])): ?>
                    <span class="error-message"><?= $errorMessages['dateNaissance'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= $pseudo ?>" placeholder="Pseudo" required />
                <?php if (isset($errorMessages['pseudo'])): ?>
                    <span class="error-message"><?= $errorMessages['pseudo'] ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="two-inputs">
            <div class="form-group">
                <label for="tel">Téléphone</label>
                <input type="text" id="tel" name="tel" value="<?= $tel ?>" placeholder="Téléphone" required pattern="[0-9]{10}" />
                <?php if (isset($errorMessages['tel'])): ?>
                    <span class="error-message"><?= $errorMessages['tel'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" required>
                    <option value="" disabled <?= empty($statut) ? 'selected' : '' ?>>Statut</option>
                    <option value="Absent" <?= $statut == 'Absent' ? 'selected' : '' ?>>Absent</option>
                    <option value="Ne pas déranger" <?= $statut == 'Ne pas déranger' ? 'selected' : '' ?>>Ne pas déranger</option>
                    <option value="En ligne" <?= $statut == 'En ligne' ? 'selected' : '' ?>>En ligne</option>
                </select>
                <?php if (isset($errorMessages['statut'])): ?>
                    <span class="error-message"><?= $errorMessages['statut'] ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="two-inputs">
            <div class="form-group">
                <label for="ancienmotdepasse">Ancien mot de passe</label>
                <input type="password" id="ancienmotdepasse" name="ancienmotdepasse" />
                <?php if (isset($errorMessages['ancienmotdepasse'])): ?>
                    <span class="error-message"><?= $errorMessages['ancienmotdepasse'] ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="two-inputs">
            <div class="form-group">
                <label for="nouveaumotdepasse">Nouveau mot de passe</label>
                <input type="password" id="nouveaumotdepasse" name="nouveaumotdepasse" />
                <?php if (isset($errorMessages['nouveaumotdepasse'])): ?>
                    <span class="error-message"><?= $errorMessages['nouveaumotdepasse'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirmationmotdepasse">Confirmation mot de passe</label>
                <input type="password" id="confirmationmotdepasse" name="confirmationmotdepasse" />
                <?php if (isset($errorMessages['confirmationmotdepasse'])): ?>
                    <span class="error-message"><?= $errorMessages['confirmationmotdepasse'] ?></span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="button-container">
            <input type="submit" name="submit" value="Enregistrer" class="input-submit">
            <input type="button" name="logout" value="Déconnexion" class="input-logout" onclick="window.location.href='logout.php';">
        </div>
        
        <?php if (isset($errorMessages['general'])): ?>
            <div class="general-error-message"><?= $errorMessages['general'] ?></div>
        <?php endif; ?>
        
        <?php
            } elseif ($_SESSION['isAdmin']) {
                echo '<input type="button" name="logout" value="Déconnexion" class="input-logout" onclick="window.location.href=\'logout.php\';">';
            } else {
                echo 'Aucun utilisateur trouvé pour le pseudo donné.';
            }
            $stmt->closeCursor(); 
        } else {
            echo "La session pseudo n'est pas définie.";
        }
        ?>
    </form>
</main>
</body>
</html>
