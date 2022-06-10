<?php
    // Interne
    require_once "../classes/Database.php";
    require_once "../classes/Utils.php";

    session_start();

    $account = $_SESSION;

    $db = new Database(); 
    $util = new Utils();
    $db->connect("localhost", "root", "dakomv2", "", "");

    if (isset($_GET["cat"]) && !empty($_GET["cat"])){
        $catFilters = $_GET["cat"];
    } else {
        $catFilters = "all";
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
        <link rel="stylesheet" href="../src/css/learn.css">

        <!-- TITLE -->
        <title>dakom - Apprendre</title>
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
                        <a href="#" data-title="Apprendre" target="_self"><li><i class="fa-solid fa-graduation-cap"></i> Learning</li></a>
                    </ul>
                    <div class="right-btn">
                        <a href="../connect/" alt="Connexion"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        <a href="../register/" alt="Inscription"><i class="fas fa-user-plus"></i> Inscription</a>
                    </div>
                </div>
            </nav>
            <main>
                <div class="home_present">
                    <h1>Apprendre</h1>
                    <p>
                        Cette section est réservée a l'apprentissage de nombreux languages de proggramation.
                        <br>
                        Vous pouvez aussi y retrouver des ressources pour vous aider à apprendre.
                    </p>
                    <div class="home_present_button">
                        <a href="#"><i class="fa-brands fa-discord"></i> Discord</a>
                    </div>
                </div>
                <div class="home_container">
                    <form method="get">
                        <select name="cat" required>
                            <option value="" disabled selected>Trier par catégorie</option>
                            <option value="all">Tout</option>
                            <option value="lua">Lua</option>
                            <option value="html">HTML</option>
                            <option value="css">CSS</option>
                            <option value="js">Javascript</option>
                        </select>
                        <button type="submit">Recherchez</button>
                    </form>

                    
                    <?php 
                        if ($catFilters == "all"){
                            $learns = $db->request("SELECT * FROM `learns` WHERE `needLogin` = 1 ORDER BY `id`");
                        } else {
                            $learns = $db->request("SELECT * FROM `learns` WHERE `needLogin` = 1 AND `category`='$catFilters' ORDER BY `id`");
                        }
                        
                        if (gettype($learns) == "array" && count($learns) > 0){
                            echo '<span class="title-home"><i class="fas fa-coins"></i> Tutoriels Payant</span>';
                            echo "<div class='learns-container'><article class='forlearn'>";
                                foreach($learns as $learn){
                                    echo "<a href='../cours/?id=" .$learn["id"]. "&login=" .($util->toBooleanString($learn["needLogin"])). "' target='_self' alt='Apprendre " .$util->reformatLanguage($learn["category"], "name"). "' title='" .$learn["title"]. "'>
                                        <article class='learn-container'>
                                            "  .($learn["new"] ? "<div class='learn-new'>NEW!</div>" : ""). "
                                            <div class='learn-img'>
                                                <img title ='Miniature " .$learn["title"]. "' src='../src/imgs/minia/conditionsEP1.jpg' alt='Miniature' height='150px' draggable='false'>
                                            </div>
                                            
                                            <div class='text-learn'>
                                                <h2 class='learn-title' title='Titre du Tutoriel'>" .$learn["title"]. " <span class='learn-category'> <i class='".$util->reformatLanguage($learn["category"], "icon")."'></i> ".$util->reformatLanguage($learn["category"], "name")."</span></h2>
                                                <p class='learn-desc' title = 'Description du Tutoriel'><i class='fas fa-text-height icon-color'></i> ".$learn["content"]."</p>
                                                
                                                <span class='learn-date' title='Date de Publication du Tutoriel'><i class='fas fa-calendar icon-color'></i> ".$learn["publishedAt"]."</span>
                                                <span class='learn-level' title='Niveau de Difficulté'><i class='fas fa-level-up-alt icon-color'></i> Débutant</span>
                                            </div>
                                        </article>
                                    </a>";
                                }
                            echo "</div>";
                        }
                    ?>

                    <span class="title-home"><i class="fas fa-coins"></i> Gratuit</span>
                    <?php
                        if ($catFilters == "all"){
                            $learns = $db->request("SELECT * FROM `learns` WHERE `needLogin` = 0 ORDER BY `id`");
                        } else {
                            $learns = $db->request("SELECT * FROM `learns` WHERE `needLogin` = 0 AND `category`='$catFilters' ORDER BY `id`");
                        }
                        
                        if (gettype($learns) == "array"){
                            if (count($learns) > 0){
                                echo "<div class='learns-container'><article class='forlearn'><h1>Tutoriels Gratuit</h1>";
                                    foreach($learns as $learn){
                                        echo "<a href='../cours/?id=" .$learn["id"]. "&login=" .($util->toBooleanString($learn["needLogin"])). "' target='_blank' alt='Apprendre " .$util->reformatLanguage($learn["category"], "name"). "' title='" .$learn["title"]. "'>
                                            <article class='learn-container'>
                                                "  .($learn["new"] ? "<div class='learn-new'>NEW!</div>" : ""). "
                                                <div class='learn-img'>
                                                    <img title ='Miniature " .$learn["title"]. "' src='../src/imgs/minia/conditionsEP1.jpg' alt='Miniature' height='150px' draggable='false'>
                                                </div>
                                                
                                                <div class='text-learn'>
                                                    <h2 class='learn-title' title='Titre du Tutoriel'>" .$learn["title"]. " <span class='learn-category'> <i class='".$util->reformatLanguage($learn["category"], "icon")."'></i> ".$util->reformatLanguage($learn["category"], "name")."</span></h2>
                                                    <p class='learn-desc' title = 'Description du Tutoriel'><i class='fas fa-text-height icon-color'></i> ".$learn["content"]."</p>
                                                    
                                                    <span class='learn-date' title='Date de Publication du Tutoriel'><i class='fas fa-calendar icon-color'></i> ".$learn["publishedAt"]."</span>
                                                    <span class='learn-level' title='Niveau de Difficulté'><i class='fas fa-level-up-alt icon-color'></i> Débutant</span>
                                                </div>
                                            </article>
                                        </a>";
                                    }
                                echo "</div>";
                            } else {
                                echo "<div class='learns-container'>
                                    <article class='forlearn'>
                                        <h1>Tutoriels Gratuit</h1>

                                        <article class='learn-container'>                                                
                                                <div class='text-learn'>
                                                    <h2 class='learn-title' title='Titre du Tutoriel'>Aucun Tutoriel disponible " .(isset($_GET["cat"]) && !empty($_GET["cat"]) ? "en " .$util->reformatLanguage($_GET["cat"], "name") : ""). "!</h2>
                                                    <span class='learn-level' title='Niveau de Difficulté'><i class='fas fa-level-up-alt icon-color'></i> Aucun Niveau</span>
                                                </div>
                                        </article>
                                </div>";
                            }
                        } else {
                            echo "Il y a une erreur avec la base de données (CODE: REQUEST_LEARN_ARRAY).";
                        }
                    ?>
                </div>
            </main>
            <div class="bottom_bar_container">
                <ul>
                    <a href="../" data-title="Acceuil"><li><i class="fa-solid fa-house-chimney"></i></li></a>
                    <a href="../forum/" data-title="Forum"><li><i class="fa-solid fa-users"></i></li></a>
                    <a href="../share/" data-title="Partages"><li><i class="fa-solid fa-share"></i></li></a>
                    <a href="#" data-title="Apprendre" target="_self"><li><i class="fa-solid fa-graduation-cap"></i></li></a>
                </ul>
            </div>
        </div>
    </body>
</html>