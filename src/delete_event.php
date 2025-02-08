<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../include/connexion.inc.php");

if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp']) && !$_SESSION['isAdmin']) {
    header("Location: login.php");
    exit();
}

$title = $_GET['title'];
$start = $_GET['start'];
$end = $_GET['end'];

$title = html_entity_decode($title);
$start = html_entity_decode($start);
$end = html_entity_decode($end);

// Extraire les dates des chaînes
$start = str_replace('Start: ', '', $start);
$end = str_replace('End: ', '', $end);

try {
    // Utilisez createFromFormat pour spécifier le format de date et d'heure
    $startDatetime = DateTime::createFromFormat('d/m/Y H:i:s', $start);
    $endDatetime = DateTime::createFromFormat('d/m/Y H:i:s', $end);

    if (!$startDatetime || !$endDatetime) {
        throw new Exception('Invalid date format');
    }
} catch (Exception $e) {
    echo "Error in date format: " . $e->getMessage();
    exit();
}

$startFormatted = $startDatetime->format('Y-m-d H:i:s');
$endFormatted = $endDatetime->format('Y-m-d H:i:s');

try {
    $req = $cnx->prepare("DELETE FROM qualite_dev.public_event WHERE type_event = :title AND date_start = :start AND date_end = :end;");
    $req->bindParam(':title', $title);
    $req->bindParam(':start', $startFormatted);
    $req->bindParam(':end', $endFormatted);

    if ($req->execute()) {
        echo "<script>alert('Événement supprimé avec succès !')</script>";
        header("Location: calendar.php");
        exit();
    } else {
        echo "<script>alert('Erreur lors de la suppression de l\'événement.')</script>";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>
