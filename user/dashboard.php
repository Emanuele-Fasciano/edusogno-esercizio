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

session_start();

// recupero email e id dello user autenticato
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $userEmail = $_SESSION["email"];
}

// uso una variabile flash per stampare il messaggio di cambio password con successo
$success_message = $_SESSION["success_message"] ??  null;
unset($_SESSION['success_message']);


// Eseguo la query per recuperare le informazioni dello user e le salvo
$sql = "SELECT name, surname FROM utenti WHERE id = $user_id";
$resultUser = $conn->query($sql);
$row = $resultUser->fetch_assoc();
$userName = $row["name"];
$userSurname = $row["surname"];


// Eseguo la query per recuperare le righe in cui l'email è contenuta nella colonna 'attendees'
$sql = "SELECT * FROM eventi WHERE FIND_IN_SET('$userEmail', attendees) > 0";

$resultEvents = $conn->query($sql);

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
</head>

<body>
    <header>
        <div class="logo">
            <img src="../images/logo.svg" alt="">
        </div>
    </header>
    <div class="actions">
        <div class="action-btn"><a href="change_password_form.php">Modifica password</a></div>
        <div class="action-btn"><a href="user_login.php">Logout</a></div>
    </div>
    <h1>Ciao <?= $userName . " " . $userSurname ?>, ecco i tuoi eventi</h1>
    <h3 class="success-message"><?= $success_message ?></h3>
    <div class='cards-container'>
        <?php
        if ($resultEvents->num_rows > 0) {
            // Scorro  i risultati della query e salvo il nome e la data dell' evento
            while ($row = $resultEvents->fetch_assoc()) {
                $nameEvent = $row["nome_evento"];
                $dateEvent = $row["data_evento"];

                // Formatto la data e l'orario
                $formattedDate = date("d/m/Y H:i", strtotime($dateEvent));

                // stampo le card con i dettagli
                echo "     
                    <div class='card'>
                        <h2 class='title'>$nameEvent</h2>
                        <h3 class='date'>$formattedDate</h3>
                        <div class='btn'>JOIN</div>
                    </div>
                    ";
            }
        } else {
            // stampo un messaggio se non ci sono eventi
            echo "<h1>Nessun evento in programma<h1/>";
        }
        ?>
    </div>
</body>

</html>