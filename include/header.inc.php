<?php
    session_start();
    include("../include/connexion.inc.php");

    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp'])) {
        header("Location: login.php");
        exit();
    }

    $req_avatar = $cnx->prepare("SELECT avatar FROM qualite_dev.utilisateur WHERE pseudo = :pseudo");
    $req_avatar->bindParam(':pseudo', $_SESSION['pseudo']);
    $req_avatar->execute();

    if ($req_avatar->rowCount() > 0) {
        $user = $req_avatar->fetch(PDO::FETCH_OBJ);
        $avatar = $user->avatar;
    }
    if ($_SESSION['isAdmin']) {
        $avatar = "https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/assets/avatar_admin.png";
    }
    $_SESSION['avatar'] = $avatar;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");
        @import url('https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&display=swap');


        body {
            margin: 0;
            padding: 0;
            font-family: "Inter", sans-serif;
            font-weight: 500;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 0 60px;
            background-color: #f0a3a3;
            height: 60px;
        }

        header .left {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header .left img {
            max-height: 100%;
            margin-right: 20px;
        }

        header .left p {
            color: #4b2828;
            margin: 0;
            font-family: "Lobster Two", sans-serif;
            font-weight: 400;
            font-size: 35px;
        }

        nav {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        nav .links {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav .links .link {
            margin: 0 10px;
        }

        nav .links .link a {
            text-decoration: none;
            color: #4b2828;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        nav .links .link a:not(.avatar) {
            padding: 20px;
        }

        nav .links .link a.avatar {
            padding: 10px;
        }

        nav .links .link a:hover{
            background-color: #dc1a1a;
            transition: 0.2s;
            color: white;
        }

        nav .links .link a img {
            height: 40px; 
            max-height: 100%;
            border-radius: 50%;
            object-fit: cover; 
        }

        @media screen and (max-width: 870px) {
            header {
                padding: 0;
            }

            header .left img {
                margin-right: 5px;
            }
        }

        @media screen and (max-width: 800px) {
            header {
                padding: 0;
            }

            header .left img {
                margin-right: 5px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="left">
            <img src="../assets/logo.png" alt="Logo du site">
            <p>SilverLove</p>
        </div>
        <nav>
            <ul class="links">
                <il class="link">
                    <a href="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/src/accueil.php">Accueil</a>
                </il>
                <il class="link">
                    <a href="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/src/friend.php">Chat</a>
                </il>
                <il class="link">
                    <a href="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/src/calendar.php">Évènements</a>
                </il>
                <il class="link">
                    <a href="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/src/agenda.php">Agenda</a>
                </il>
                <il class="link">
                    <a href="https://perso-etudiant.u-pem.fr/~julien.synaeve/silverlove/src/account.php" class="avatar">
                        <img src="
                        <?php
                            echo $avatar;
                        ?>
                        " alt="Avatar du compte">
                    </a>
                </il>
            </ul>
        </nav>
    </header>
    
</body>
</html>