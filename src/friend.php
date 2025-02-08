<?php
    session_start();
    include("../include/connexion.inc.php");
    include("../include/header.inc.php");

    // Check if the user is logged in
    if (!isset($_SESSION['pseudo']) || !isset($_SESSION['mdp'])) {
        header("Location: login.php");
        exit();
    }
    $sec = $_SESSION['refresh_sec'];

    $idsenior = $_SESSION['idsenior'];

    try {
        // Prepare the SQL statement
        $stmt = $cnx->prepare("
            SELECT DISTINCT u.idsenior, u.nom, u.pseudo, u.avatar
            FROM qualite_dev.utilisateur u
            JOIN qualite_dev.message m ON (u.idsenior = m.idsenior_init OR u.idsenior = m.idsenior_dest)
            WHERE :idsenior IN (m.idsenior_init, m.idsenior_dest) AND u.idsenior != :idsenior
        ");
        $stmt->execute(['idsenior' => $idsenior]);
        $conversationPartners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt_all_users = $cnx->prepare("SELECT idsenior, nom, pseudo, avatar FROM qualite_dev.utilisateur");
        $stmt_all_users->execute();
        $allUsers = $stmt_all_users->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur dans la requête : ' . htmlspecialchars($e->getMessage());
    }
    // Function to check if a user is in the conversation partners list
    function isConversationPartner($userId, $conversationPartners) {
        foreach ($conversationPartners as $partner) {
            if ($partner['idsenior'] == $userId) {
                return true;
            }
        }
        return false;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <title>SilverLove | Ami</title>
    <link rel="stylesheet" href="../css/chat.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
</head>
<body>
<h1>Conversations</h1>
<main class="container-conversations">
    <h2>Continuez de discuter avec eux</h2>
    <?php
        if (!empty($conversationPartners)) {
            foreach ($conversationPartners as $partner) {
                echo '<a href="chat.php?id=' . urlencode($partner['idsenior']) . '" class="link-conv">';
                echo "<div class='conversation'>";
                echo '<img src="' . $partner['avatar'] . '" alt="">';
                echo "<p>" . htmlspecialchars($partner['pseudo']) . "</p>";
                echo "</div>";
                echo "</a>";
            }
        } else {
            echo "<p>Aucun ami avec qui vous avez échangé des messages.</p>";
        }
    ?>
    <h2>Tous les utilisateurs</h2>
    <?php
        if (!empty($allUsers)) {
            foreach ($allUsers as $user) {
                echo '<a href="chat.php?id=' . urlencode($user['idsenior']) . '" class="link-conv">';
                echo "<div class='conversation'>";
                echo '<img src="' . $user['avatar'] . '" alt="">';
                echo "<p>" . htmlspecialchars($user['pseudo']);
                if ($user['idsenior'] == $_SESSION['idsenior']) {
                    echo ' <span class="italic-opacity">(Vous)</span>';
                } elseif (isConversationPartner($user['idsenior'], $conversationPartners)) {
                    echo ' <span class="italic-opacity">(Ami)</span>';
                }
                echo "</p>";
                echo "</div>";
                echo "</a>";
            }
        } else {
            echo "<p>Aucun utilisateur trouvé.</p>";
        }
    ?>
</main>
</body>
</html>
