<?php
session_start();

$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera la nuova password e la conferma inviata dal form HTML
    $new_password = $_POST["new_password"];
    $password_confirm = $_POST["password_confirm"];

    // connessione al database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "edusogno_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    if ($new_password === $password_confirm) {
        // Escapo la nuova password per prevenire SQL injection
        $new_password = $conn->real_escape_string($new_password);

        // Recupera l'ID dell'utente autenticato dalla sessione
        $user_id = $_SESSION["user_id"];

        // Esegui l'aggiornamento della password nel database
        $update_password_query = "UPDATE utenti SET password = ? WHERE id = ?";
        $new_query = $conn->prepare($update_password_query);
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $new_query->bind_param("si", $hashed_password, $user_id);

        if ($new_query->execute()) {
            // Password aggiornata con successo
            $_SESSION['success_message'] = "Password cambiata con successo!";
            header("Location: dashboard.php");
        } else {
            // Errore nell'aggiornamento della password
            $error_message = "Si è verificato un errore nell'aggiornamento della password.";
        }

        $conn->close();
    } else {
        $error_message = "Le due password inserite devono essere uguali";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edusogno-Cambio password</title>
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
    <div class="back-btn"><a href="dashboard.php">Indietro</a></div>
    <h1>Cambia Password</h1>
    <h4><?= $error_message ?></h4>
    <div class="form-container">
        <form action="change_password_form.php" method="post">
            <label for="password">Nuova Password:</label>
            <input type="password" id="password" name="new_password" placeholder="Inserisci la nuova password"
                required><br>
            <label for="password_confirm">Conferma Nuova Password:</label>
            <input type="password" id="password_confirm" name="password_confirm"
                placeholder="Inserisci la nuova password" required><br>
            <input type="submit" value="Cambia Password" class="btn">
        </form>
    </div>
</body>

</html>