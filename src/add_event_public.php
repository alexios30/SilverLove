<?php
    session_start();
    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp']) && !$_SESSION['isAdmin']) {
        header("Location: login.php");
        exit();
    }

    include("../include/connexion.inc.php");
    $title = $_POST['title'];
    $details = $_POST['details'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];

    $req = "INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end) VALUES (:title, :details, :date_start, :date_end)";
    $stmt = $cnx->prepare($req);
    $result = $stmt->execute([':title' => $title, ':details' => $details, ':date_start' => $date_start, ':date_end' => $date_end,]);

    if ($result) {
        echo "<script>alert('Événement ajouté avec succès !')</script>";
        header("Location: calendar.php");
        exit();
    } else {
        echo "<script>alert('Erreur lors de l\'ajout de l\'événement.')</script>";
    }
?>