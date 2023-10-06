<?php
session_start();

// stabilisco la connesione con il database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "edusogno_db";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// variabili flash per il feedback di aggiunta o modifica evento
$success_message = $_SESSION['add_event_message'] ?? $_SESSION['update_event_message'] ?? null;
unset($_SESSION['add_event_message']);
unset($_SESSION['update_event_message']);

// recupero email e id dell admin autenticato
if (isset($_SESSION["admin_id"])) {
    $admin_id = $_SESSION["admin_id"];
}

// Eseguo la query per recuperare le informazioni dell' admin e le salvo
$sql = "SELECT name, surname FROM admins WHERE id = $admin_id";
$resultUser = $conn->query($sql);
$row = $resultUser->fetch_assoc();
$adminName = $row["name"];
$adminSurname = $row["surname"];


// Eseguo la query per recuperare tutti gli eventi dal database
$sql = "SELECT * FROM eventi";
$resultAllEvents = $conn->query($sql);

// chiudo la connessione con il databse
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- link css -->
    <link rel="stylesheet" href="../css/dashboard-style.css">

    <!-- link font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

    <!-- link fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>
        <div class="logo">
            <img src="../images/logo.svg" alt="">
        </div>
    </header>

    <div class="actions">
        <div class="action-btn"><a href="admin_login.php">Logout</a></div>
        <div class="action-btn" class="btn btn-primary"><a href="add_event_form.php">Aggiungi evento</a></div>
    </div>

    <h1>Benvenuto admin <?= $adminName . " " . $adminSurname ?>, ecco la lista di tutti gli eventi</h1>
    <h3 class="success-message"><?= $success_message ?></h4>
        <div class='cards-container'>
            <?php

            // se nel database c'è almeno un evento
            if ($resultAllEvents->num_rows > 0) {
                // Scorro  i risultati della query e salvo i dettagli dell' evento
                while ($row = $resultAllEvents->fetch_assoc()) {
                    $nameEvent = $row["nome_evento"];
                    $dateEvent = $row["data_evento"];
                    $idEvent = $row["id"];
                    $attendees = $row["attendees"];

                    // Formatto la data e l'orario
                    $formattedDate = date("d/m/Y H:i", strtotime($dateEvent));

                    // stampo le card con i dettagli
                    echo "     
                    <div class='card'>
                        <h2 class='title'>$nameEvent</h2>
                        <h3 class='date'>$formattedDate</h3>
                        <div class='options'>
                            <form method='POST' action='../event/delete_event.php'>
                                <input type='hidden' name='idEvent' value='$idEvent'>
                                <button type='submit' class='delete-btn'><i class='fa-solid fa-trash'></i></button>
                            </form>
                            <div  class='update-btn'><a href='update_form.php?idEvent=$idEvent'><i class='fa-solid fa-pen'></i></a></div>
                        </div>
                    </div>  
    ";
                }
            } else {
                // stampo un messaggio se non ci sono eventi
                echo "<h1>Nessun evento in programma<h1 />";
            }
            ?>
        </div>
</body>

</html>