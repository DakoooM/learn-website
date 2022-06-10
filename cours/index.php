<?php
    // Interne
    require_once "../classes/Database.php";
    require_once "../classes/Utils.php";

    session_start();

    $db = new Database(); 
    $util = new Utils();
    $db->connect("localhost", "root", "dakomv2", "", "");

    $needPay = false;
    $foundRole = false;
    $actualCours = "none";

    if ( !empty($_GET["id"]) && isset($_GET["id"]) && !empty($_GET["login"]) && isset($_GET["login"]) ){
        $id = $_GET["id"];
        $login = $_GET["login"];
        $loginToInt = ($login == "true" ? 1 : 0);

        if ($login == "false"){
            $result = $db->request("SELECT * FROM learns WHERE id = '" .$id. "' AND needLogin = '" .$loginToInt. "'");

            if ($result !== null && count($result) > 0){
                $actualCours = [
                    "id" => $result[0]["id"] == null ? "" : $result[0]["id"],
                    "title" => $result[0]["title"] == null ? "dakom - Cours" : $result[0]["title"],
                    "content" => $result[0]["content"] == null ? "" : $result[0]["content"],
                    "needLogin" => $result[0]["needLogin"] == null ? "" : $result[0]["needLogin"]
                ];
            } else {
                echo "<div class='top-error'>Le cour n'existe pas!</div>";
            }
        } else if ($login == "true"){
            $needPay = true;
            if (!empty($_SESSION) && isset($_SESSION) && $_SESSION["connected"] == true){
                foreach(json_decode($_SESSION["user"][0]["roles"]) as $key => $value){
                    if ($value == "Admin" or $value == "Teacher" or $value == "Student"){
                        $foundRole = true;
                    }
                }

                if ($foundRole == true){
                    $result = $db->request("SELECT * FROM learns WHERE id = '" .$id. "' AND needLogin = '" .$loginToInt. "'");

                    if ($result !== null && count($result) > 0){
                        $actualCours = [
                            "id" => $result[0]["id"] == null ? "" : $result[0]["id"],
                            "title" => $result[0]["title"] == null ? "dakom - Cours" : $result[0]["title"],
                            "content" => $result[0]["content"] == null ? "" : $result[0]["content"],
                            "needLogin" => $result[0]["needLogin"] == null ? "" : $result[0]["needLogin"],
                            "idVideo" => $result[0]["idVideo"] == null ? "" : $result[0]["idVideo"],
                            "codes" => $result[0]["codes"] == null ? "no" : $result[0]["codes"],
                            "comments" => $result[0]["comments"] == null ? "no" : $result[0]["comments"]
                        ];
                    }
                } else {
                    echo "<div class='top-error'>Vous n'avez pas accès a cette page!</div>";
                }
            }
        } else {
            echo "<div class='top-error'>Vous n'avez pas acces a cette page (TYPE NUMBER)</div>";
        }

        if (isset($_POST["comment_text"]) && !empty($_POST["comment_text"])) {
            if ($actualCours["comments"] == "no" || $actualCours["comments"] == "Array") {
                $comment_text = $_POST["comment_text"];
                $comment = [
                    "author" => $_SESSION["user"][0]["name"] ? $_SESSION["user"][0]["name"] : "Utilisateur Anonyme",
                    "content" => $comment_text
                ];

                $result = $db->request("UPDATE learns SET comments = '" .json_encode($comment). "' WHERE id = '" .$id. "'");
            } else {
                $newComment = [
                    "auteur" => $_SESSION["user"][0]["name"] ? $_SESSION["user"][0]["name"] : "Utilisateur Anonyme", 
                    "content" => $_POST["comment_text"], 
                    "replies" => []
                ];

                $actualComments = json_decode($actualCours["comments"]);
                array_push($actualComments, $newComment);
                $_SESSION["lastComments"] = json_encode($actualComments);
                $lastMessageDB = "";

                try {
                    $result = $db->request("UPDATE learns SET comments = '" .json_encode($actualComments). "' WHERE id = '" .$id. "'");
                    $lastMessageDB = "<div class='top-error'>" .json_decode($_SESSION["lastComments"])["auteur"]. "Vous avez ajouté un commentaire!</div>";
                    $_SESSION["lastCommentMessage"] = $lastMessageDB;
                } catch (Exception $e) {
                    $lastMessageDB = "<div class='top-error'>Erreur lors de l'ajout du commentaire (DB_UPDATE_SYNTAX)<br>{$e}!</div>";
                    $_SESSION["lastCommentMessage"] = $lastMessageDB;
                }
            }

            unset($_POST["comment_text"]);
            header("Location: {$PHP_SELF}?id=" .$id. "&login=" .$login. "#");
            sleep(2);
        }

        // var_dump("TEST", $_SESSION["lastComments"], "TEST", $_SESSION["lastCommentMessage"]);
        if (isset($_SESSION["lastCommentMessage"]) && !empty($_SESSION["lastCommentMessage"])){
            echo $_SESSION["lastCommentMessage"];
        } else {
            echo "Flemme";
        }

        
    } else {
        echo "<div class='top-error'>Aucun Cours n'est disponible (Veuillez vérifier votre URL)</div>";
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
        <meta name="twitter:title" content="dakom - Cours">
        <meta name="twitter:description" content="dakom est un site internet permettant d'apporter de l'aide au personnes voulant apprendre le développement.">
        <meta name="twitter:site" content="dakom official Website">

        <!-- CSS -->
        <link rel="stylesheet" href="../src/css/reset.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../src/css/lib/highlight.css"/> <!-- libs -->
        <link rel="stylesheet" href="../src/css/home.css">
        <link rel="stylesheet" href="../src/css/cours.css">

        <!-- SCRIPTS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/highlight.min.js" integrity="sha512-yUUc0qWm2rhM7X0EFe82LNnv2moqArj5nro/w1bi05A09hRVeIZbN6jlMoyu0+4I/Bu4Ck/85JQIU82T82M28w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>hljs.highlightAll();</script>
        <script src="../src/js/cours.js"></script>

        <!-- TITLE -->
        <title><?php 
            if ($actualCours !== "none"){
                echo $actualCours["title"];
            } else {
                echo "dakom - Cours";
            }
        ?></title>
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
                        <a href="../learn/?cat=all" data-title="Apprendre"><li><i class="fa-solid fa-graduation-cap"></i> Learning</li></a>
                    </ul>
                    <div class="right-btn">
                        <a href="../connect/" alt="Connexion"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        <a href="../register/" alt="Inscription"><i class="fas fa-user-plus"></i> Inscription</a>
                    </div>
                </div>
            </nav>
            <main>
                <div class="home_container">
                    <?php if ($needPay == true && $foundRole == false): ?>
                        <?php include "notExist.html"; ?>
                    <?php elseif ($needPay == true && $foundRole == true): ?>
                        <?php if ($actualCours !== "none"): ?>
                            <div class="cours_container">
                                <h1><?=$actualCours["title"]?><sup>Épisode n°<?=$actualCours["id"]?></sup></h1>
                                <span data-desc><?=$actualCours["content"]?></span>
                                <iframe src="../video_player/?q=<?=$actualCours['idVideo']?>" title="DakoM Video Player" height="750" width="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                                
                                <div class="cours_code">
                                    <?php if ($actualCours["codes"] !== "no"): ?>
                                        <?php foreach (json_decode($actualCours["codes"]) as $code): ?>
                                            <span data-title><?=$code->title?></span>
                                            <pre><code class="code hljs language-<?=$code->language?>"><?=$code->code_source?></code></pre>
                                        <?php endforeach; ?>
                                    <?php endif;?>
                                </div>
                                <div class="comments-container">
                                    <span class="comment-one">Publier un commentaire</span>
                                    <form method="post" class="comments-container">
                                        <textarea class="comment-text" placeholder="Écrire un commentaire..." name="comment_text" cols="50" rows="10" required></textarea>
                                        <input type="submit" class="submit-comment" value="Envoyez le commentaire!">
                                    </form>
                                    
                                    <?php if ($actualCours["comments"] !== "no"): ?>
                                        <hr>
                                        <?php foreach(json_decode($actualCours["comments"]) as $comment): ?>
                                            <div class="comments">
                                                <div class="comments-title">
                                                    <img src="../src/imgs/user.png" draggable="False" alt="User Logo" height="30px" class="user-logo">
                                                    <span class="author-comments" draggable="false"><?=$comment->auteur?></span>
                                                    <span class="response-comments">Répondre</span>
                                                </div>
                                                <p class="comments-text" draggable="false"><?=$util->replaceLinkByTag(nl2br($comment->content))?></p>
                                                <div class="comments-0-response"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>Aucun commentaire n'a été publiée!</p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="bottom-notification" id = "bottom_notif">
                                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum, recusandae.</p>
                            </div>
                        <?php else: ?>
                            <?php include "notExist.html"; ?>
                        <?php endif; ?>
                    <?php endif;?>
                </div>
            </main>
            <footer>
                <div class="bottom_bar_container">
                    <ul>
                        <a href="../" data-title="Acceuil"><li><i class="fa-solid fa-house-chimney"></i></li></a>
                        <a href="../forum/" data-title="Forum"><li><i class="fa-solid fa-users"></i></li></a>
                        <a href="../share/" data-title="Partages"><li><i class="fa-solid fa-share"></i></li></a>
                        <a href="#" data-title="Apprendre" target="_self"><li><i class="fa-solid fa-graduation-cap"></i></li></a>
                    </ul>
                </div>
            </footer>
        </div>
    </body>
</html>