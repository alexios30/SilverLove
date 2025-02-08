<?php
    session_start();
    include("../include/connexion.inc.php");
    include("../include/header.inc.php");

    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp'])) {
        header("location: login.php");
        exit();
    }
    $sec = $_SESSION['refresh_sec'];
    $currentSenior = $_SESSION['idsenior'];

    try {
        // Récupérer les partenaires de conversation existants
        $stmt = $cnx->prepare("
            SELECT DISTINCT u.idsenior, u.nom, u.pseudo, u.avatar
            FROM qualite_dev.utilisateur u
            JOIN qualite_dev.message m ON (u.idsenior = m.idsenior_init OR u.idsenior = m.idsenior_dest)
            WHERE :idsenior IN (m.idsenior_init, m.idsenior_dest) AND u.idsenior != :idsenior
        ");
        $stmt->execute(['idsenior' => $currentSenior]);
        $conversationPartners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer tous les utilisateurs en ligne sauf les partenaires de conversation existants et le senior actuel
        $stmt_all_users = $cnx->prepare("
            SELECT idsenior, nom, pseudo, statut, avatar
            FROM qualite_dev.utilisateur
            WHERE statut = 'En ligne' AND idsenior != :currentSenior AND idsenior NOT IN (
                SELECT u.idsenior
                FROM qualite_dev.utilisateur u
                JOIN qualite_dev.message m ON (u.idsenior = m.idsenior_init OR u.idsenior = m.idsenior_dest)
                WHERE :idsenior IN (m.idsenior_init, m.idsenior_dest) AND u.idsenior != :idsenior
            )
        ");
        $stmt_all_users->execute(['idsenior' => $currentSenior, 'currentSenior' => $currentSenior]);
        $allUsers = $stmt_all_users->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur dans la requête : ' . htmlspecialchars($e->getMessage());
    }

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
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL=<?php echo $page?>'">
    <title>SilverLove | Accueil</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/accueil.css">
</head>
<body>
    <main>
        <h1>Accueil</h1>
        <div class="content">
            <h3>Discuter avec des profils qui pourrait vous intéresser</h3>
            <div class="users-section">
                <?php
                    if (!empty($allUsers)) {
                        foreach ($allUsers as $user) {
                            echo '<a href="chat.php?id=' . urlencode($user['idsenior']) . '" class="link-conv">';
                            echo "<div class='conversation'>";
                            echo '<img src="' . $user['avatar'] . '" alt="">';
                            echo "<div class='user-info'>";
                            echo "<p class='user-name'>" . htmlspecialchars($user['pseudo']);
                            if ($user['idsenior'] == $_SESSION['idsenior']) {
                                echo ' <span class="italic-opacity">(Vous)</span>';
                            } elseif (isConversationPartner($user['idsenior'], $conversationPartners)) {
                                echo ' <span class="italic-opacity">(Ami)</span>';
                            }
                            echo "</p>";
                            echo '<p class="statut">' . htmlspecialchars($user['statut']) . '</p>';
                            echo "</div>";
                            echo "</div>";
                            echo "</a>";
                        }
                    } else {
                        echo "<p>Aucun utilisateur trouvé.</p>";
                    }
                ?>
            </div>
            <h3>Consulter les évènements du jour et de demain</h3>
            <div class="events-section">
                <?php
                    $tomorrow = date('Y-m-d', strtotime('+1 day'));
                    $today = date('Y-m-d H:i:s');
                    
                    $req = "
                        SELECT type_event, details, date_start, date_end 
                        FROM qualite_dev.public_event 
                        WHERE (date(date_start) = :tomorrow) 
                        OR (date(date_start) = :today_date AND date_start >= :current_time) 
                        ORDER BY date_start
                    ";
                    
                    $stmt = $cnx->prepare($req);
                    $stmt->bindParam(':tomorrow', date('Y-m-d', strtotime('+1 day')), PDO::PARAM_STR);
                    $stmt->bindParam(':today_date', date('Y-m-d'), PDO::PARAM_STR);
                    $stmt->bindParam(':current_time', $today, PDO::PARAM_STR);
                    $stmt->execute();
                    
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $event) {
                        $startTimeHour = date('H:i', strtotime($event['date_start']));
                        $endTimeHour = date('H:i', strtotime($event['date_end']));
                        $startTimeDay = date('d/m', strtotime($event['date_start']));
                        $endTimeDay = date('d/m', strtotime($event['date_end']));
                        
                        echo '<a href="calendar.php" class="link-event">';
                        echo '<div class="evenement">';
                        echo "<div class='event-info'>";
                        echo "<p class='event-name'>" . htmlspecialchars($event['type_event']) . "</p>";
                        echo "<p class='event-details'>" . htmlspecialchars($event['details']) . "</p>";
                        echo "<p class='event-time'>Séance de " . htmlspecialchars($startTimeHour) . " à " . htmlspecialchars($endTimeHour) . "</p>";
                        echo "<p class='event-time'>Le " . htmlspecialchars($startTimeDay) . "</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }
                ?>
            </div>
        </div>
    </main>    
</body>
</html>
