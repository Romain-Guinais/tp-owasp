<?php
require_once('functions.php');
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if (isset($_GET['email']) && isset($_GET['password'])) {
    $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
    $password = $_GET['password'];

    if ($email && $password) {
        $user = logUser($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } else {
        echo "Veuillez entrer des informations valides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ma super app sécurisée</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu"
          crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php if(!$user): ?>
    <h1>Connexion</h1>
    <form action="/" method="GET">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
            <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre email avec qui que ce soit.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" required>
        </div>
        <div class="form-group">
            <label for="stayConnected">Rester connecté</label>
            <input name="stayConnected" type="checkbox" id="stayConnected">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <a href="register.php">Je m'inscris</a>
    <?php else: ?>
        <h1>Bienvenue <?= htmlspecialchars($user['email']) ?></h1>
        <a href="informations.php?id=<?= htmlspecialchars($user['id']) ?>">Mes informations</a><br/>
        <a href="logout.php">Logout</a>
    <?php endif ?>
</div>
</body>
</html>
