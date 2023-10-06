<?php
session_start();

// Setto una variabile null per l'errore
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // stabilisco la connesione con il database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "edusogno_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Salvo l'email e la password inviate dal modulo 
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Query per selezionare l'utente tramite l'email
    $emailQuery = "SELECT id, email, password FROM utenti WHERE email = ?";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // se non ci sono risultati 
    if ($result->num_rows == 0) {
        $error_message = "email o password errate, riprovare";
    } elseif ($result->num_rows == 1) { // se c'è un riscontro nel database
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];


        // Verifico la password
        if (password_verify($password, $storedPassword)) {
            // Se la password è corretta, esegui l'accesso e salvo in session id e mail dello user
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["email"] = $email;
            header("Location: dashboard.php"); // Redirect alla pagina personale dell'utente
            exit();
        } else {

            // Se la password è errata, imposta il messaggio di errore
            $error_message = "email o password errate, riprovare";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edusogno-Login user</title>
    <link rel="icon" type="image/png" href="../images/edusogno-favicon.png">

    <!-- link fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- link font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

    <!-- link css -->
    <link rel="stylesheet" href="../css/login-style.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../images/logo.svg" alt="">
        </div>
    </header>
    <h1>Hai già un account?</h1>
    <div class="error-message">
        <h4><?= $error_message ?></h4>
    </div>
    <div class="form-container">
        <form action="user_login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="name@example.com" required><br>
            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Scrivila qui" required><br>
                <i class="fa-solid fa-eye" id="show-password"></i>
            </div>
            <input type="submit" value="Accedi" class="btn">
        </form>

        <div class="link-button">
            <a href="registration_form.php">Non hai ancora un profilo? <strong>Registrati</strong>.</a>
        </div>
        <div class="link-button">
            <a href="../admin/admin_login.php">Admin access</a>
        </div>
    </div>
</body>

<!-- script per mostrare la password scritta -->
<script>
const passwordInput = document.getElementById('password');
const showPasswordBtn = document.getElementById('show-password');

showPasswordBtn.addEventListener("click", function() {
    if (passwordInput.type === "text") {
        passwordInput.type = "password";
    } else {
        passwordInput.type = "text";
    }
});
</script>

</html>
<html>