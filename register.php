<?php
require_once('functions.php');
require 'vendor/autoload.php';

use ZxcvbnPhp\Zxcvbn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    if ($username && $email && $password && $recaptcha_response) {
        $zxcvbn = new Zxcvbn();
        $passwordStrength = $zxcvbn->passwordStrength($password);

        // Vérification de la force du mot de passe
        if ($passwordStrength['score'] < 2) {
            echo "Le mot de passe est trop faible. Veuillez choisir un mot de passe plus fort.";
        } else {
            // Vérification du CAPTCHA
            $recaptcha_secret = '6Lf2J_cpAAAAAJWkRER3202bxTD1WO-HdH7pSNEU';
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
            $responseKeys = json_decode($response, true);

            if (intval($responseKeys["success"]) !== 1) {
                echo 'Veuillez compléter le CAPTCHA.';
            } else {
                // Hashage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Sauvegarde de l'utilisateur
                $result = saveUser($username, $email, $hashedPassword);
                if ($result === true) {
                    header('Location: index.php');
                    exit;
                } else {
                    echo "Une erreur est survenue : " . htmlspecialchars($result);
                }
            }
        }
    } else {
        echo "Veuillez entrer des informations valides.";
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
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <div class="g-recaptcha" data-sitekey="6Lf2J_cpAAAAAFdt7fFEsMTqDen_ZAfJmtGz7Jqa"></div>
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
