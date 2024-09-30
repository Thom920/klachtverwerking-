<?php
    require '../vendor/autoload.php';

    use Symfony\Component\Dotenv\Dotenv;

    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/.env');


//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    die('PHPMailer is niet geladen!');
}

// Haal de formuliergegevens op
$naam = $_POST['naam'] ?? 'Naam onbekend';
$email = $_POST['email'] ?? 'Geen e-mail ingevuld';
$klacht = $_POST['klacht'] ?? 'Geen klacht beschreven';

// Maak een nieuw PHPMailer-object aan
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Debug info weergeven
    $mail->isSMTP();                                            // Verstuur via SMTP
    $mail->Host       = 'smtp.gmail.com';                       // SMTP-server
    $mail->SMTPAuth   = true;                                   // SMTP authenticatie inschakelen
    $mail->Username   = $_ENV("username");              // Jouw Gmail-adres
    $mail->Password   = $_ENV("password");                // App-specifiek wachtwoord
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Beveiliging instellen
    $mail->Port       = 465;                                    // Poort



    // Ontvangers instellen
    $mail->setFrom($_ENV("username"), 'Klachtenservice');
    $mail->addAddress($_ENV("username"), 'Thom User');  // Je eigen e-mailadres als ontvanger

    // E-mailinhoud
    $mail->isHTML(true);                                        // Instellen op HTML e-mail
    $mail->Subject = 'Nieuwe klacht van ' . $naam;
    $mail->Body    = "
        <h1>Nieuwe klacht ontvangen</h1>
        <p><strong>Naam:</strong> {$naam}</p>
        <p><strong>E-mail:</strong> {$email}</p>
        <p><strong>Klacht:</strong><br>{$klacht}</p>
    ";
    $mail->AltBody = "Naam: {$naam}\nE-mail: {$email}\nKlacht: {$klacht}";

    // E-mail versturen
    $mail->send();
    echo 'De klacht is succesvol verzonden.';
} catch (Exception $e) {
    echo "De klacht kon niet worden verzonden. Mailer Error: {$mail->ErrorInfo}";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klachtenformulier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Klachtenformulier</h2>
    <form action="verwerk_klacht.php" method="POST">
        <label for="naam">Naam</label>
        <input type="text" id="naam" name="naam" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="klacht">Omschrijving van de klacht</label>
        <textarea id="klacht" name="klacht" rows="5" required></textarea>

        <input type="submit" value="Verzend Klacht">
    </form>
</div>

<div class="footer">
    <p>&copy; 2024 Klantenservice</p>
</div>

</body>
</html>