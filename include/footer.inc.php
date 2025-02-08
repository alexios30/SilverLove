<?php
    session_start();
    include("../include/connexion.inc.php");

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
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Inter", sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        footer {
            background-color: #f0a3a3;
        }

        footer .nav {
            list-style: none;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .nav a {
            text-decoration: none;
            color: black;
        }

        .nav img {
            height: 40px;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <footer>
        <ul class="nav">
            <a href="">
                <li class="button">
                    <img src="../assets/house_black.svg" alt="Home">
                </li>
            </a>
            <a href="">
                <li class="button">
                    <img src="../assets/chat_black.svg" alt="Chat">
                </li>
            </a>
            <a href="">
                <li class="button">
                    <img src="../assets/calendar_black.svg" alt="Calendar">
                </li>
            </a>
            <a href="">
                <li class="button">
                    <img src="../assets/profile_black.svg" alt="Profil">
                </li>
            </a>
        </ul>
    </footer>
</body>
</html>