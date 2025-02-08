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
if (!isset($_GET['id']) || $_GET['id'] == $_SESSION['idsenior']) {
    header("location: friend.php");
    exit();
}
$idami = $_GET['id'];
$errorMessages = [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="refresh" content="<?php echo $sec; ?>;URL='<?php echo $page . '?id=' . urlencode($idami); ?>'">
    <title>SilverLove | Ami</title>
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
                $idami = $_GET['id'];
                $stmt = $cnx->prepare("SELECT * FROM qualite_dev.utilisateur WHERE idsenior = :idsenior");
                $stmt->bindParam(':idsenior', $idami, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
                    $nom = $ligne['nom'];
                    $prenom = $ligne['prenom'];
                    $dateNaissance = $ligne['datenaissance'];
                    $avatar = $ligne['avatar'];
                    $pseudo = $ligne['pseudo'];
                    $statut = $ligne['statut'];

            ?>
                <div class="avatar-options">
                        <label>
                            <img src="<?= $avatar?>" alt="Avatar">
                        </label>
                </div>

                <div class="two-inputs">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <p id="nom"><?= htmlspecialchars($nom) ?></p>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <p id="prenom"><?= htmlspecialchars($prenom) ?></p>
                    </div>
            </div>

            <div class="two-inputs">
                <div class="form-group">
                    <label for="dateNaissance">Naissance</label>
                    <p id="datenaissance"><?= htmlspecialchars($dateNaissance) ?></p>
                    <button type="button" class="btn-agenda" id="btn-agenda" onclick="addBirthday()">Ajouter à l'agenda</button>
                </div>

                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <p id="pseudo"><?= htmlspecialchars($pseudo) ?></p>
                </div>
            </div>

            <div class="two-inputs">
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <p id="statut"><?= htmlspecialchars($statut) ?></p>
                </div>
            </div>


            
            <?php if (isset($errorMessages['general'])): ?>
                <div class="general-error-message"><?= $errorMessages['general'] ?></div>
            <?php endif; ?>
            
            <?php
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

    <script>
    function addBirthday() {      
        console.log("Button clicked");
            
        const title = "Anniversaire de " + document.getElementById("prenom").textContent;
        const start = document.getElementById("datenaissance").textContent;
        const end = start;
        const isBirthday = true;
        
        console.log("Title:", title);
        console.log("Start:", start);
        console.log("End:", end);
        
        window.location.replace("calendar.php?title=" + encodeURIComponent(title) + "&start=" + encodeURIComponent(start) + "&end=" + encodeURIComponent(end)) + "&isBirthday=" + encodeURIComponent(isBirthday);
    }
    </script>
            <?php 
                if (isset($_SESSION['pseudo']) && $_SESSION['isAdmin'] && isset($_GET['id'])) {
                    echo '<form method="POST">';
                    echo '<input type="submit" name="submit" value="Supprimer le compte" class="input-submit">';
                    echo ' </form>';

                    if (isset($_POST['submit'])) {
                        if (isset($_GET['id'])) {
                            try {
                                $idami = $_GET['id'];

                                $stmt1 = $cnx->prepare("DELETE FROM qualite_dev.utilisateur WHERE idsenior = :id");
                                $stmt2 = $cnx->prepare("DELETE FROM qualite_dev.message WHERE (idsenior_init = :idinit OR idsenior_dest = :iddest)");
                                $stmt3 = $cnx->prepare("DELETE FROM qualite_dev.event_perso WHERE idsenior = :idsenior");

                                $stmt1->bindParam(':id', $idami, PDO::PARAM_STR);
                                $stmt2->bindParam(':idinit', $idami, PDO::PARAM_STR);
                                $stmt2->bindParam(':iddest', $idami, PDO::PARAM_STR);
                                $stmt3->bindParam(':idsenior', $idami, PDO::PARAM_STR);

                                $stmt1->execute();
                                $stmt2->execute();
                                $stmt3->execute();

                            } catch (Exception $e) {
                                echo "Erreur lors de la suppression du compte : " . $e->getMessage();
                            }
                        } 
                    } 
                }
            ?>
</body>
</html>
