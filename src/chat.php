<?php
    session_start();
    include("../include/connexion.inc.php");
    include("../include/header.inc.php");
    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp'])) {
        header("location: login.php");
        exit();
    }
    if (!isset($_GET['id']) || $_GET['id'] == $_SESSION['idsenior']) {
        header("location: friend.php");
        exit();
    }
    $sec = $_SESSION['refresh_sec'];
    $idami = $_GET['id'];

    try {
        // Requête pour récupérer les informations de l'ami
        $stmt_ami = $cnx->prepare("SELECT pseudo, avatar FROM qualite_dev.utilisateur WHERE idsenior = :idami");
        $stmt_ami->execute(['idami' => $idami]);
        $ami = $stmt_ami->fetch(PDO::FETCH_ASSOC);

        if (!$ami) {
            header("location: friend.php");
            exit();
        }

        // Requête pour récupérer les messages de la conversation
        $stmt = $cnx->prepare("
            SELECT 
                m.contenu, 
                m.dateenvoi, 
                sender.pseudo AS sender_name, 
                recipient.pseudo AS recipient_name,
                m.idsenior_init,
                m.idsenior_dest
            FROM qualite_dev.message m
            JOIN qualite_dev.utilisateur sender ON m.idsenior_init = sender.idsenior
            JOIN qualite_dev.utilisateur recipient ON m.idsenior_dest = recipient.idsenior
            WHERE (m.idsenior_init = :idsenior AND m.idsenior_dest = :idami) OR (m.idsenior_init = :idami AND m.idsenior_dest = :idsenior)
            ORDER BY m.dateenvoi ASC
        ");
        $idsenior = $_SESSION['idsenior'];
        $stmt->execute(['idsenior' => $idsenior, 'idami' => $idami]);
        
    } catch (PDOException $e) {
        echo 'Erreur dans la requête : ' . htmlspecialchars($e->getMessage());
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL=<?php echo $page?>">
    <title>SilverLove | Conversation</title>
    <link rel="stylesheet" href="../css/chat.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
</head>
<body>
    <div class="top">
        <a href="javascript:void(0);" onclick="goBack();" class="button back"><i class="fa-solid fa-left-long"></i>Retour</a>
        <h1>Conversations</h1>
        <a href="friend_account.php?id=<?php echo urlencode($idami); ?>" class="button profil">
            <p>Profil</p>
            <img src="<?php echo htmlspecialchars($ami['avatar']); ?>" alt="Avatar de l'ami">
        </a>
    </div>
    <main class="container-conversations">
    <?php
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='message'>";
                echo "<h3>" . ($row['sender_name']) .  "</h3>";
                echo "<p>" . ($row['contenu']) . "</p>";
                echo "<span class='date'>" . ($row['dateenvoi']) . "</span>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucun message trouvé.</p>";
        }

        if(isset($_POST['message'])) {
            $message = $_POST['message'];
            $date = date("Y-m-d H:i:s");
            $idsenior = $_SESSION['idsenior'];
            $idami = $_GET['id'];

            try {
                $requete = $cnx->prepare("
                    INSERT INTO qualite_dev.message (contenu, dateenvoi, idsenior_init, idsenior_dest)
                    VALUES (:contenu, :date, :idsenior_init, :idsenior_dest)
                ");

                $success = $requete->execute([
                    ':contenu' => $message,
                    ':date' => $date,
                    ':idsenior_init' => $idsenior,
                    ':idsenior_dest' => $idami
                ]);
                
                // Rafraîchir la page après l'envoi du message
                header("Location: chat.php?id=" . urlencode($idami));
                exit();

            } catch (PDOException $e) {
                echo 'Erreur dans la requête : ' . $e->getMessage();
            }
        }
    ?>
        <form action="chat.php?id=<?php echo urlencode($idami); ?>" method="post" class="formulaire">
            <input type="text" placeholder="Ecrire un message..." name="message" id="message" class="input" required />
            <input type="submit" value="Envoyer" class="input-submit" />
        </form>
    </main>
    
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>