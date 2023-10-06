<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Includo la classe EventController
    include "EventController.php";

    // Ottieni l'ID dell'evento da eliminare dalla richiesta POST
    $idEvent = $_POST["idEvent"];

    // Creo un'istanza di EventController
    $eventController = new EventController();

    // Richiamo la funzione deleteEvent per eliminare il record al database
    if ($eventController->deleteEvent($idEvent)) {
        header("Location: ../admin/admin_dashboard.php"); // Redirect alla pagina personale dell' admin
        exit();
    } else {
        echo "Errore durante l'eliminazione del record nel database.";
    }
}
