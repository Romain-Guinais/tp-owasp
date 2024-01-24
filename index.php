<?php
require_once('functions.php');
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if (isset($_GET['email']) && isset($_GET['password'])) {
    $users = logUser($_GET['email'], $_GET['password']);
    if(!empty($users)) {
        $user = $users[0];
        $_SESSION['user'] = $user;
    }
}
?>

<html>
<head>
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
</head>
<body>
<div class="container">
    <?php if(!$user): ?>
    <h1>Connexion</h1>
    <form action="/" method="GET">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1">
        </div>
        <div class="form-group">
            <label for="stayConnected">Rester connecté</label>
            <input name="stayConnected" type="checkbox" id="stayConnected">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <a href="register.php">Je m'inscrit</a>
    <?php else: ?>
        <h1>Bienvenue <?= $user->email ?></h1>
    <a href="informations.php?id=<?= $user->id ?>">Mes informations</a><br/>
    <a href="logout.php">Logout</a>
    <?php endif ?>
</div>
</body>
</html>