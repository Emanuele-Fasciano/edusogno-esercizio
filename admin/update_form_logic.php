<?php

session_start();

// Includo la classe EventController
include "../event/EventController.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupero i dati dal modulo HTML
    $id = $_POST["idEvent"];
    $title = $_POST["title"];
    $attendees = $_POST["attendees"];
    $description = $_POST["description"];
    $date = $_POST["date"];

    // Creo un'istanza di EventController
    $eventController = new EventController();

    // Richiamo la funzione addEvent per aggiungere il record al database
    $eventController->updateEvent($title, $attendees, $description, $date, $id);
    $_SESSION['update_event_message'] = "Evento modificato con successo!";
    header("Location: admin_dashboard.php"); // Redirect alla pagina personale dell' admin

}
