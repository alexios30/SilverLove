<?php
session_start();

try {
    include("../include/connexion.inc.php");
    include("../include/header.inc.php");

    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp'])) {
        header("Location: login.php");
        exit();
    }

    $idsenior = $_SESSION['idsenior'];

    if (isset($_GET['title']) && isset($_GET['start']) && isset($_GET['end'])) {
        $title = $_GET['title'];
        $start = $_GET['start'];
        $end = $_GET['end'];

        $title = html_entity_decode($title);
        $start = html_entity_decode($start);
        $end = html_entity_decode($end);

        $details = "Details";

        if ($start == $end) {
            try {
                $birthday = DateTime::createFromFormat('Y-m-d', $start);
                if (!$birthday) {
                    throw new Exception("Invalid date format for birthday.");
                }

                $currentYear = (int)date('Y');
                $nextBirthday = DateTime::createFromFormat('m-d', $birthday->format('m-d'));
                $nextBirthday->setDate($currentYear, (int)$birthday->format('m'), (int)$birthday->format('d'));

                if ($nextBirthday < new DateTime()) {
                    $nextBirthday->modify('+1 year');
                }

                $startFormatted = $nextBirthday->format('Y-m-d 00:00:00');
                $endFormatted = $nextBirthday->format('Y-m-d 23:59:59');

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                exit();
            }
        } else {
            $start = str_replace('Start: ', '', $start);
            $end = str_replace('End: ', '', $end);

            $startDate = DateTime::createFromFormat('d/m/Y H:i:s', $start);
            $endDate = DateTime::createFromFormat('d/m/Y H:i:s', $end);

            if (!$startDate || !$endDate) {
                echo "<script>alert('Date format is incorrect.')</script>";
                exit();
            }

            $startFormatted = $startDate->format('Y-m-d H:i:s');
            $endFormatted = $endDate->format('Y-m-d H:i:s');
        }
        
        try {
            $req = "SELECT * FROM qualite_dev.event_perso WHERE type_event = :title AND date_start = :start AND date_end = :end AND idsenior = :idsenior";
            $stmt = $cnx->prepare($req);
            $stmt->execute([':title' => $title, ':start' => $startFormatted, ':end' => $endFormatted, ':idsenior' => $idsenior]);
            $result = $stmt->fetch();

            if ($result) {
                echo "<script>alert('Cet évènement existe déjà.')</script>";
            } else {
                $req = "INSERT INTO qualite_dev.event_perso (type_event, details, date_start, date_end, idsenior) VALUES (:title, :details, :start, :end, :idsenior)";
                $stmt = $cnx->prepare($req);
                $result = $stmt->execute([':title' => $title, ':details' => $details, ':start' => $startFormatted, ':end' => $endFormatted, ':idsenior' => $idsenior]);

                if ($result) {
                    echo "<script>alert('Événement ajouté avec succès !')</script>";
                    header("Location: calendar.php");
                    exit();
                } else {
                    echo "<script>alert('Erreur lors de l\'ajout de l\'événement.')</script>";
                }
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo "<script>alert('An error occurred while processing your request. Please try again later.')</script>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SilverLove | Calendrier</title>
    <link rel="stylesheet" href="../fullcalendar/core/main.css" />
    <link rel="stylesheet" href="../fullcalendar/daygrid/main.css" />
    <link rel="stylesheet" href="../fullcalendar/timegrid/main.css">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/calendar.css">
  </head>
  <body>
    <main>
        <h1>Calendrier des évènements</h1>
        <div id="calendrier"></div>
    </main>

    <?php
        if (isset($_SESSION['pseudo']) && $_SESSION['isAdmin']) {
            ?>
            <section class="addevent">
                <h2>Ajouter un évènement</h2>
                <form action="add_event_public.php" method="post">
                    <input type="text" name="title" id="title" placeholder="Titre" required><br />
                    <input type="text" name="details" id="details" placeholder="Description" required><br/>
                    <label for="date_start">Début de l'évènement</label>
                    <input type="datetime-local" name="date_start" id="date_start"required><br />
                    <label for="date_end">Fin de l'évènement</label>
                    <input type="datetime-local" name="date_end" id="date_end" required><br />
                    <input type="submit" value="Ajouter" class="input-submit" /><br />
                </form>
            </section>
            <?php
        }
    ?>

    <div id="eventModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="eventTitle"></h2>
        <p id="eventStart"></p>
        <p id="eventEnd"></p>
        <p id="eventDetails"></p>
        <?php
            if (!$_SESSION['isAdmin']) {
                echo '<button id="addEventButton" onclick="addEvent()">S\'inscrire</button>';
            }
        ?>
        <?php
            if (isset($_SESSION['pseudo']) && $_SESSION['isAdmin']) {
                echo'
                <button id="removeEventButton">Supprimer l\'évènement</button>
                ';
            }
        ?>
      </div>
    </div>

    <?php
		$req = "SELECT type_event, details, date_start, date_end FROM qualite_dev.public_event";
		$stmt = $cnx->prepare($req);
		$stmt->execute();
		$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$jsEvents = array();
		foreach ($events as $event) {
			$start = strtotime($event['date_start']);
    	$end = strtotime($event['date_end']);

			$fullDay = (($end - $start) == 86399);

			$jsEvents[] = array(
				'title' => $event['type_event'],
				'start' => $event['date_start'],
				'end' => $event['date_end'],
				'allDay' => $fullDay,
			);
		}

		$jsonEvents = json_encode($jsEvents, JSON_UNESCAPED_UNICODE);
    ?>

	<script>
		let evenements = <?php echo $jsonEvents; ?>;
		console.log(evenements);
	</script>

    <script src="../fullcalendar/core/main.js"></script>
    <script src="../fullcalendar/daygrid/main.js"></script>
    <script src="../fullcalendar/timegrid/main.js"></script>
    <script src="../js/script.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("addEventButton").onclick = function () {
        console.log("Button clicked");

        const title = document.getElementById("eventTitle").textContent;
        const start = document.getElementById("eventStart").textContent;
        const end = document.getElementById("eventEnd").textContent;

        console.log("Title:", title);
        console.log("Start:", start);
        console.log("End:", end);

        window.location.replace("calendar.php?title=" + encodeURIComponent(title) + "&start=" + encodeURIComponent(start) + "&end=" + encodeURIComponent(end));
    };
      document.getElementById("removeEventButton").onclick = function () {
        const title = document.getElementById("eventTitle").textContent;
        const start = document.getElementById("eventStart").textContent;
        const end = document.getElementById("eventEnd").textContent;

        console.log("Remove button clicked");

        window.location.replace("delete_event.php?title=" + encodeURIComponent(title) + "&start=" + encodeURIComponent(start) + "&end=" + encodeURIComponent(end));
      };
    });
    </script>
  </body>
</html>