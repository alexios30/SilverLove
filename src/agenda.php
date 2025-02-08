<?php
    session_start();
    include("../include/connexion.inc.php");
    include("../include/header.inc.php");
    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp'])) {
        header("location: login.php");
        exit();
    }
    $idsenior = $_SESSION['idsenior'];
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SilverLove | Agenda</title>
    <link rel="stylesheet" href="../fullcalendar/core/main.css" />
    <link rel="stylesheet" href="../fullcalendar/daygrid/main.css" />
    <link rel="stylesheet" href="../fullcalendar/timegrid/main.css">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/calendar.css">
  </head>
  <body>
    <main>
        <h1>Agenda personnel</h1>
        <div id="calendrier"></div>
    </main>

    <?php
		$stmt = $cnx->prepare("SELECT type_event, details, date_start, date_end FROM qualite_dev.event_perso WHERE idsenior = $idsenior;");
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
		evenements = <?php echo $jsonEvents; ?>;
		console.log(evenements);
	</script>

    <script src="../fullcalendar/core/main.min.js"></script>
    <script src="../fullcalendar/daygrid/main.min.js"></script>
    <script src="../fullcalendar/timegrid/main.min.js"></script>
    <script src="../fullcalendar/interaction/main.min.js"></script>
    <script src="../js/script.js"></script>
  </body>
</html>
