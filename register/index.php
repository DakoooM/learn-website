<?php
    // Interne
    require_once "../classes/Database.php";
    require_once "../classes/Utils.php";

    session_start();

    $account = $_SESSION;

    $db = new Database(); 
    $util = new Utils();
    $connected = $db->connect("localhost", "root", "dakomv2", "", "");
    $message = "";

    if ($connected == false){
        $message = "<p class='error_created'><i class='fas fa-times'></i> Veuillez contacter un administrateur (ERROR: CONNECT_STATE_DATABASE)</p>";
        echo $message;
    }

    if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['mail']) && !empty($_POST['mail'])) {
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
        $mail = $_POST['mail'];
        $mdp = md5($password);

        $accountExist = $db->request("SELECT * FROM `accounts` WHERE (`name` = '".$pseudo."' OR `email` = '" .$mail. "')");

        if (count($accountExist) > 0) {
            $message = "<p class='error_created'><i class='fas fa-times'></i> Pseudonyme et/ou Adresse mail déja utiliser!</p>";
        } else {
            try {
                $roles = json_encode(["Membre"]);
                $db->request("INSERT INTO accounts (name, email, mdp, roles, connected) VALUES ('".$pseudo."', '".$mail."', '".$mdp."', '" .$roles. "', '0')");
                $message = "<p class='success_created'><i class='fas fa-check'></i> Votre compte a correctement été créé " .$pseudo. "</p>";
                header("Location: ../connect");
            } catch (Exception $e) {
                $message = "<p class='error_created'><i class='fas fa-times'></i> Erreur lors de la création de votre compte (contacter un administrateur → REQUEST_REGISTER_SYNTAX)<br><br>$e</p>";
            }
        }

        unset($_POST["pseudo"]);
        unset($_POST["mail"]);
        unset($_POST["password"]);
    } else {
        $message = "<p class='error_created'><i class='fas fa-times'></i> Veuillez remplir tous les champs!</p>";
    }
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../src/imgs/logo.png" type="image/png">

        <!-- Meta Informations -->
        <meta name="description" content="dakom est un site internet permettant d'apporter de l'aide au personnes voulant apprendre le développement.">
        <meta name="theme-color" content="black">
        <meta name="color" content="purple">
        <meta name="copyright" content="©dakom 2022">
        <meta name="keywords" content="scripts, fivem, fivem script, fivem scripts, fivem ressource, rageui, RageUI, RageUI FiveM, FiveM Store, fivem store, francais, anglais, english, game, games, heberg, hebergeur, hebergeurs, hebergé, heberger, scripts, resources, resource, dev, developpement, developpe, coding, code, game-code, esx, esx-scripts, fivem-store, store-fivem, fivem store, esx scripts, scripts esx, optimiser, optimisation, program, programs, programming, hud, job, fivem job, fivem creator, fivem hud, fivem mapping, fivem map, legal, fivem legal, fivem illegal, adns job, adns hud, adns map, adns mapping, adns mlo, mlo, mapping mlo, gta5, grand theft auto, gtarp, gta5rp, gta rp script, gta5 rp, fivem gang, gang fivem, gang, drogues fivem, drugs fivem, fivem drugs, drogues fivem, activités fivem, fivem activité, activity fivem, fivem activity, mission fivem, fivem mission, fivem launcher, fivem download, download fivem, anticheat fivem, fivem anticheat, anticheat, creator fivem, fivem creator, jeux-vidéo">
        <link rel="canonical" href="https://dakom.fr/">
    
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="../src/imgs/logo.png">
        <meta name="twitter:creator" content="DakoM">
        <meta name="twitter:title" content="dakom - Apprendre">
        <meta name="twitter:description" content="dakom est un site internet permettant d'apporter de l'aide au personnes voulant apprendre le développement.">
        <meta name="twitter:site" content="dakom official Website">

        <!-- CSS -->
        <link rel="stylesheet" href="../src/css/reset.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../src/css/home.css">
        <link rel="stylesheet" href="../src/css/log.css">

        <!-- Javascript -->
        <script src="../src/js/register.js"></script>

        <!-- TITLE -->
        <title>dakom - Inscription</title>
    </head>
    <body>
        <div class="container">
            <nav>
                <div class="navbar_container">
                    <div class="left-logo">
                        <a href="../" alt="acceuil dakom" title="Acceuil">
                            <img src="../src/imgs/logo.png" height="60px" draggable="false" alt="logo">
                        </a>
                    </div>
                    <ul>
                        <a href="../" data-title="Acceuil"><li><i class="fa-solid fa-house-chimney"></i> Acceuil</li></a>
                        <a href="../forum/" data-title="Forum"><li><i class="fa-solid fa-users"></i> Entraide</li></a>
                        <a href="../share/" data-title="Partages"><li><i class="fa-solid fa-share"></i> Partages</li></a>
                        <a href="../learn/" data-title="Apprendre"><li><i class="fa-solid fa-graduation-cap"></i> Learning</li></a>
                    </ul>
                    <div class="right-btn">
                        <a href="../connect/" alt="Connexion"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        <a href="#" alt="Inscription"><i class="fas fa-user-plus"></i> Inscription</a>
                    </div>
                </div>
            </nav>
            <main>
                <div class="home_present">
                    <h1>Inscription</h1>
                    <p>Cette section est réservée a votre inscription.</p>
                    <p>Cela vous permettera d'effectuer des actions exclusive (Achats des cours, Partager des ressources...)</p>
                    <div class="home_present_button">
                        <a href="#"><i class="fa-brands fa-discord"></i> Discord</a>
                        <a href="../connect/"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    </div>
                </div>
                <div class="register_container">
                    <form method="post">
                        <label for="text">Pseudo</label>
                        <input type="text" name="pseudo" id="pseudonyme" placeholder="Pseudonyme" minlength="4" maxlength="8" required>
                        <label for="email">Email</label>
                        <input type="email" name="mail" id="email" placeholder="youremail@gmail.com" required>
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                        <input type="submit" value="S'inscrire">
                        <?=$message?>
                    </form>
                </div>
            </main>
            <div class="bottom_bar_container">
                <ul>
                    <a href="../" data-title="Acceuil"><li><i class="fa-solid fa-house-chimney"></i></li></a>
                    <a href="../forum/" data-title="Forum"><li><i class="fa-solid fa-users"></i></li></a>
                    <a href="../share/" data-title="Partages"><li><i class="fa-solid fa-share"></i></li></a>
                    <a href="../learn/" data-title="Apprendre"><li><i class="fa-solid fa-graduation-cap"></i></li></a>
                </ul>
            </div>
        </div>
    </body>
</html>