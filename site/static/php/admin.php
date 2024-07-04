<?php
// Inclusion du fichier "fonction.php" qui contient des fonctions nécessaires
require_once("fonction.php");

// Démarrage de la session
session_start();

// Récupération des valeurs des paramètres GET "login" et "pw" dans les variables de session
$_SESSION["login"] = $_GET["login"];
$_SESSION["pw"] = $_GET["pw"];

// Appel de la fonction "connexion" pour établir une connexion à la base de données
$sql = connexion();

// Préparation de la requête SQL pour sélectionner les lignes de la table "ADMIN" correspondant au login et mot de passe fournis
$requete = $sql->prepare("SELECT * FROM ADMIN WHERE Login=:login AND  Password=:password;");
$requete->bindParam(":login", $_SESSION["login"]);
$requete->bindParam(":password", $_SESSION["pw"]);
$requete->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <title>ks</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- Inclusion des fichiers CSS et JavaScript externes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="../js/nightmode.js"></script>
    <script src="../js/suggestion.js"></script>
    <link id="main" href="../css/main.css" rel="stylesheet">
    <link id="style" href="../css/jour.css" rel="stylesheet">

    <script>
        // Récupérer la valeur du paramètre "alert" dans l'URL
        const alertParam = new URLSearchParams(window.location.search).get('alert');

        // Vérifier la valeur du paramètre "alert" et afficher l'alerte correspondante
        if (alertParam === 'erreurcompte') {
            alert('Login ou mot de passe erroné.');
        }
        else if (alertParam === 'dejaclef') {
            alert('La clef est déjà dans la base de données.');
        }
        else if (alertParam === 'clefmanquante'){
            alert("Ajoutez d'abord la clé à la base de données.")
        }
        else if (alertParam === 'dejakanji'){
            alert("Le kanji est déjà enregistré dans la base de données.")
        }
        else if (alertParam === 'kanjimanquant'){
            alert("Ajoutez d'abord le kanji à la base de données.")
        }
        else if (alertParam === 'dejamot'){
            alert("Le mot est déjà enregistré dans la base de données.")
        }
        else if (alertParam === 'supprimer'){
            alert("Supprimé avec succès")
        }
        else if (alertParam === 'success') {
            alert('Ajout avec succès.');
        }
    </script>


</head>

<body class="modejour">
<!--Menu qui permet de se deconnecter-->
<nav class="navbar navbar-expand-lg navbar-light bg-transparent">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">KanjiSensei</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
                    <a class="nav-link" href="../../templates/admin.html">Déconnection</a>
        </div>
        <div class="form-check form-switch" style="padding-right: 2%">
            <input class="form-check-input" type="checkbox" role="switch" id="night">
            <label class="form-check-label" for="night" id="moon_light"><img id="test" src="../images/1.png" width="20" height="20" alt="soleil dans un croissant de lune"></label>
        </div>
    </div>
</nav>

<hr>
<?php

    if ($requete->rowCount() == 0){
        //retour sur la page de connexion pas dans la base de données
        header("Location: ../../templates/admin.html?alerte=supprimer");
        exit();
    }
    else if ($requete->rowCount() > 0) {
        echo "<h2>Bonjour ".$_SESSION["login"]."</h2>"
?>
<div class="accordion">
<!--    Affichage des sugestions-->
    <div class="accordion-item">
        <h3 class="accordion-header" id="headingSugg">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSugg" aria-expanded="true" aria-controls="collapseSugg">
            Les suggestions des utilisateurs
            </button>
        </h3>
        <div id="collapseSugg" class="accordion-collapse collapse" aria-labelledby="headingSugg" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <ul>
                    <?php

                    $suggList = array();

                    // requête pour l'affichage de l'ensemble des suggestion
                    $requete = $sql->prepare("SELECT DISTINCT Suggestion FROM SUGGESTIONS;");
                    $requete->execute();

                    while ($ligne = $requete->fetch(PDO::FETCH_OBJ)) {
                    $suggList[] =  $ligne->Suggestion;
                    echo '<li name="'. $ligne->Suggestion .'">' . $ligne->Suggestion . '</li>';
                    }

                    echo "<form method='get' action='./fonctionadmin.php'>
                             <div class='row'>
                        <div class='col'>
                        <input type='text' placeholder='Supprimer une suggestion' list='supsugg' name='supsugg' class='form-control' required>
                        <datalist id='supsugg'>";

                            foreach ($suggList as $suggest){
                            echo"<option value='" .$suggest."'>";
                            }

                            echo "</datalist></div>";

                        echo  "<div class='col'><input type='submit' name='supprimer_suggestions' value='Supprimer'></div></div>
                    </form>";
                        ?>

                </ul>
            </div>
        </div>
    </div>

<!--    Ajouter une clé-->
    <div class="accordion-item">
        <h3 class="accordion-header" id="headingKey">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKey" aria-expanded="true" aria-controls="collapseKey">
                Ajouter une clé
            </button>
        </h3>
        <div id="collapseKey" class="accordion-collapse collapse" aria-labelledby="headingKey" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form id="addcle" method="get" action="./fonctionadmin.php">
                    <div class="row">
                        <div class="col">
                            <input id="addkey" class="form-control" placeholder="Clef" name="addkey" required maxlength="1">
                        </div>
                        <div class="col">
                            <input id="clefsens" class="form-control" placeholder="Sens" name="clefsens" required>
                        </div>
                    </div>
                    <input type="submit" name="keysubmit" value="Valider">
                </form>
            </div>
        </div>
    </div>

<!--    Ajouter un kanji-->
    <div class="accordion-item">
        <h3 class="accordion-header" id="headingKanji">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKanji" aria-expanded="true" aria-controls="collapseKanji">
                Ajouter un kanji
            </button>
        </h3>
        <div id="collapseKanji" class="accordion-collapse collapse" aria-labelledby="headingKanji" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form id="addkanji" method="get" action="./admin.php">
                    <div class="row">

                        <div class="col">
                            <input id="kanji" class="form-control" placeholder="Kanji" name="kanji" required maxlength="1">
                        </div>
                        <div class="col">
                            <input id="key" class="form-control" placeholder="Clef" name="key" required>
                        </div>
                        <div class="col">
                            <input id="sens" class="form-control" placeholder="Signification" name="sens" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input id="kunyomi" class="form-control" placeholder="Kunyomi" name="kunyomi">
                        </div>
                        <div class="col">
                            <input id="onyomi" class="form-control" placeholder="Onyomi" name="onyomi">
                        </div>
                        <div class="col">
                            <input id="nbtraits" class="form-control" placeholder="Nombre de traits" name="nbtraits" required>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jlpt" id="1" value="N1" checked>
                            <label class="form-check-label" for="inlineRadio1">N1</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jlpt" id="2" value="N2">
                            <label class="form-check-label" for="inlineRadio2">N2</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jlpt" id="3" value="N3">
                            <label class="form-check-label" for="inlineRadio1">N3</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jlpt" id="4" value="N4">
                            <label class="form-check-label" for="inlineRadio2">N4</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jlpt" id="5" value="N5">
                            <label class="form-check-label" for="inlineRadio1">N5</label>
                        </div>
                    </div>

                    <input type="submit"  name="kanjisubmit" value="Valider">
                </form>
            </div>
        </div>
    </div>

<!--    Ajouter un mot et une phrase-->
    <div class="accordion-item">
        <h3 class="accordion-header" id="headingWord">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWord" aria-expanded="true" aria-controls="collapseWord">
                Ajouter un mot et une phrase
            </button>
        </h3>
        <div id="collapseWord" class="accordion-collapse collapse" aria-labelledby="headingWord" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form id="addmot" method="get" action="./admin.php">
                    <div class="row">
                        <div class="col">
                            <input id="requiredkanji" class="form-control" placeholder="Kanji" name="requiredkanji" required maxlength="1">
                        </div>
                        <div class="col">
                        <input id="mot" class="form-control" placeholder="Mot" name="mot" required>
                        </div>
                        <div class="col">
                        <input id="motlecture" class="form-control" placeholder="Lecture" name="motlecture" required>
                        </div>
                        <div class="col">
                        <input id="mottraduction" class="form-control" placeholder="Traduction" name="mottraduction" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input id="phrase" class="form-control" placeholder="Phrase" name="phrase" required>
                            <input id="phraselecture" class="form-control" placeholder="Lecture" name="phraselecture" required>
                            <input id="phrasetraduction" class="form-control" placeholder="Traduction" name="phrasetraduction" required>
                        </div>
                    </div>
                    <input type="submit" name="wordsubmit" value="Valider">
                </form>

            </div>
        </div>
    </div>
</div>

<?php
    }
?>

<!--Footer-->
<footer>
    <div>
        <img src="../images/bird.png" alt="footer"/>
        <p>by Kenza</p>
    </div>
</footer>
</body>
</html>

