<?php
require_once('functions.php');
require 'vendor/autoload.php';

use ZxcvbnPhp\Zxcvbn;

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if ($username && $email && $password) {
        $zxcvbn = new Zxcvbn();
        $passwordStrength = $zxcvbn->passwordStrength($password);
        // Il faut au moins un mdp de force moyenne
        if ($passwordStrength['score'] < 2) {
            echo "Le mot de passe est trop faible. Veuillez choisir un mot de passe plus fort.";
        } else {
            $result = saveUser($username, $email, $password);
            if($result === true) {
                header('Location: index.php');
                exit;
            } else {
                echo "Une erreur est survenue " . htmlspecialchars($result);
            }
        }
    } else {
        echo "Invalid input";
    }
}
?>

<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ma super app sécurisée - Inscription</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Inscription</h1>
    <form action="/register.php" method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez un nom d'utilisateur.
            </div>
        </div>
        <div class="form-group">
            <label for="email">Adresse email :</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez une adresse email valide.
            </div>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez un mot de passe.
            </div>
            <div id="password-strength" class="progress mt-2">
                <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div id="password-strength-text" class="mt-2"></div>
        </div>
        <div class="form-group">
            <label for="password-confirm">Confirmez le mot de passe :</label>
            <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
            <div class="invalid-feedback">
                S'il vous plaît confirmez votre mot de passe.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <script>
        var password = document.getElementById("password");
        var confirm_password = document.getElementById("password-confirm");
        var passwordStrengthBar = document.getElementById("password-strength-bar");
        var passwordStrengthText = document.getElementById("password-strength-text");

        function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Les mots de passe ne correspondent pas");
                return false;
            } else {
                confirm_password.setCustomValidity('');
                return true;
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;

        password.addEventListener('input', function() {
            var result = zxcvbn(password.value);
            var strength = ["Très faible", "Faible", "Moyen", "Fort", "Très fort"];
            var score = result.score;
            passwordStrengthBar.style.width = (score + 1) * 20 + '%';
            passwordStrengthBar.setAttribute('aria-valuenow', (score + 1) * 20);
            passwordStrengthBar.className = 'progress-bar';
            passwordStrengthText.textContent = "Force du mot de passe : " + strength[score];
            
            switch (score) {
                case 0:
                case 1:
                    passwordStrengthBar.classList.add('bg-danger');
                    break;
                case 2:
                    passwordStrengthBar.classList.add('bg-warning');
                    break;
                case 3:
                    passwordStrengthBar.classList.add('bg-info');
                    break;
                case 4:
                    passwordStrengthBar.classList.add('bg-success');
                    break;
            }
        });

        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false || !validatePassword()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</div>
</body>
</html>
