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

// Ricevo e salvo i  dati dal form 
$name = $_POST["name"];
$surname = $_POST["surname"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT); // crypto la password da salvare nel database

// Controllo l'unicità della mail
$check_email = "SELECT * FROM utenti WHERE email = '$email'";
$result = $conn->query($check_email);

// se la mail è già presente nel database
if ($result->num_rows > 0) {
    header("Location: registration_form.php");
    $_SESSION['email_error'] = "La tua email è già stata utilizzata, inserisci una nuova email";
} else {
    // Se la mail è nuova inserisco i dati nella tabella degli utenti
    $sql = "INSERT INTO utenti (name, surname, email, password) VALUES ('$name', '$surname', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: registration-confirmed.php"); // Redirect alla pagina personale dell'utente
    } else {
        echo "Errore durante la registrazione dell'utente: " . $conn->error;
    }

    // Chiudi la connessione al database
    $conn->close();
}
