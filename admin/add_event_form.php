<?php

session_start();

// Includo la classe EventController
include "../event/EventController.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupero i dati dal form HTML
    $title = $_POST["title"];
    $attendees = $_POST["attendees"];
    $description = $_POST["description"];
    $date = $_POST["date"];

    // Creo un'istanza di EventController
    $eventController = new EventController();

    // Richiamo la funzione addEvent per aggiungere il record al database
    $eventController->addEvent($title, $attendees, $description, $date);
    header("Location: admin_dashboard.php"); // Redirect alla pagina personale dell' admin
    $_SESSION['add_event_message'] = "Evento aggiunto con successo!";
}
?>

<!DOCTYPE html>

<head>


</head>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edusogno-Aggiungi evento</title>
<link rel="icon" type="image/png" href="../images/edusogno-favicon.png">
<!-- link css -->
<link rel="stylesheet" href="../css/form-style.css">

<!-- link font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../images/logo.svg" alt="">
        </div>
    </header>
    <div class="back-btn"><a class="btn" href="admin_dashboard.php">Indietro</a></div>
    <h1>Aggiungi evento</h1>
    <div class="form-container">
        <form action="add_event_form.php" method="POST">
            <label for="title">Titolo:</label>
            <input type="text" name="title" id="title" required>

            <label for="attendees">Partecipanti:</label>
            <input type="text" name="attendees" id="attendees" required>

            <label for="description">Descrizione:</label>
            <input type="text" name="description" id="description" required>
            <label for="date">Data:</label>
            <input type="datetime-local" name="date" id="date" required>

            <input type="submit" class="btn" value="Aggiungi evento">
        </form>
    </div>

</body>

</html>