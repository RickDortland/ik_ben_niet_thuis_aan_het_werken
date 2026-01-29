<?php
session_start();
require_once 'kringloop_centrum_duurzaam/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("SELECT * FROM gebruiker WHERE gebruikersnaam = ?");
    $stmt->execute([trim($_POST['gebruikersnaam'])]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify(trim($_POST['wachtwoord']), $user['wachtwoord'])) {

        $_SESSION['gebruiker'] = $user;

        switch ($user['rollen']) {
            case 'directie':
                header("Location: users/admin/admintaak.php");
                break;

            case 'magazijnmedewerker':
                header("Location: users/magazijnmedewerker/magazijnmedewerker.php");
                break;

            case 'winkelpersoneel':
                header("Location: users/winkelpersoneel/winkelpersoneel.php");
                break;

            case 'chauffeur':
                header("Location: users/chauffeur/chauffeur.php");
                break;

            default:
                $error = "Onbekende gebruikersrol";
        }

        exit;

    } else {
        $error = "Gebruikersnaam of wachtwoord onjuist";
    }
}

?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<form method="post">
    <h2>Inloggen</h2>

    <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
    <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>

    <button type="submit">Login</button>

    <a href="forgot_password.php">Wachtwoord vergeten?</a>

    <?php if (isset($error)): ?>
        <p style="color:red; text-align:center;"><?= $error ?></p>
    <?php endif; ?>
</form>

</body>
</html>
