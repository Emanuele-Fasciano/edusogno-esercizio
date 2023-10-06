<?php

// stabilisco la connesione con il database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "edusogno_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// recupero l'id dell' evento
$idEvent = $_GET['idEvent'];

// recupero i dati dell' evento per stamparli nel form come "value" all'apertura della pagina
$sql = "SELECT attendees, nome_evento, data_evento, description  FROM eventi WHERE id = $idEvent";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$attendees = $row["attendees"];
$title = $row["nome_evento"];
$date = $row["data_evento"];
$description = $row["description"];



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edusogno-Aggiorna evento</title>
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
    <div class="back-btn"><a href="admin_dashboard.php">Indietro</a></div>
    <h1>Modifica evento</h1>
    <div class="form-container">
        <form action="update_form_logic.php" method="POST">
            <input type="hidden" name="idEvent" value="<?php echo $idEvent ?>">

            <label for="title">Titolo:</label>
            <input type="text" name="title" id="title" value="<?= $title ?>" required>

            <label for="attendees">Partecipanti:</label>
            <input type="text" name="attendees" id="attendees" value="<?= $attendees ?>" required>

            <label for="description">Descrizione:</label>
            <input type="text" name="description" id="description" value="<?= $description ?>" required></input>

            <label for="date">Data:</label>
            <input type="datetime-local" name="date" id="date" value="<?= $date ?>" required>

            <input type="submit" class="btn" value="Modifica">
        </form>
    </div>

</body>

</html>