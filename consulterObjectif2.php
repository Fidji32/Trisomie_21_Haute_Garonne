<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <title>Consulter objectif</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" href="style/style.css">
</head>
<script src="js/javascript.js"></script>
<?php
session_start();
require('QUERY.php');
testConnexion();
?>

<body>
    <div class="svgWaveContains">
        <div class="svgWave"></div>
    </div>

    <?php faireMenu();
    if (isset($_POST['redirect'])) {
        $_SESSION['objectif'] = $_POST['redirect'];
    }
    ?>
    <form method="POST">
        <h1 style="margin-top: 100px; margin-bottom: 20px">Consulter objectif :
            <?php
            if (isset($_POST['redirect'])) {
                echo afficherUnIntituleObjectif($_POST['redirect']) . "  " . nomPrenomEnfant($_SESSION['enfant']);
            } else {
                echo afficherUnIntituleObjectif($_SESSION['objectif']) . "  " . nomPrenomEnfant($_SESSION['enfant']);
            }
            ?>
        </h1>

        <?php
        if (isset($_POST['butonResetSceance'])) {
            reinitialiserObjectif($_SESSION['objectif']);
        }
        // $flag = 0;
        // if ($flag == 0 && recupererTempsDebutObjectif($_SESSION['objectif']) != 0) {
        //     if ((recupererTempsDebutObjectif($_SESSION['objectif']) - time()) <= 0)
        //         $flag = 1;
        // }
        if ((recupererTempsDebutObjectif($_SESSION['objectif']) - time()) <= 0) {
            reinitialiserObjectif($_SESSION['objectif']);
        }
        echo '<button type="submit" name="butonResetSceance" class="boutonValider"><img src="images/valider.png" class="imageIcone" alt="icone valider"><span>Reset</span></button>';
        if (isset($_POST['butonDebutSceance']) && (recupererTempsDebutObjectif($_SESSION['objectif']) - time()) <= 0 && recupererTempsDebutObjectif($_SESSION['objectif']) == 0) {
            reinitialiserObjectif($_SESSION['objectif']);
            $maintenantPlusDureeSecondes = time() + recupererDureeUnObjectif($_SESSION['objectif']) * 3600;
            ajouterTempsDebutObjectif($maintenantPlusDureeSecondes, $_SESSION['objectif']);
            echo '<h1> dedans </h1>';
        }

        // echo '<h1>' . $flag . '</h1>';
        echo '<h1> dehors </h1>';
        $test = (recupererTempsDebutObjectif($_SESSION['objectif'])  - time());
        echo $test;
        if ((recupererTempsDebutObjectif($_SESSION['objectif']) - time()) > 0) {
            $maintenant = time();
            $restant = recupererTempsDebutObjectif($_SESSION['objectif']) - $maintenant;
            $heureRestante = $restant / 60;
            $duree = dureeStringMinutes($heureRestante);
            echo '<h1>Temps restant : ' . $duree . '</h1>';
            echo '<h1> dedans 2</h1>';
        } else {
            // if ((recupererTempsDebutObjectif($_SESSION['objectif'])  - time()) <= 0) {
            echo '<h1> dedans 3</h1>';
            echo '<h1>Séance terminée !</h1>';
            echo '<button type="submit" name="butonDebutSceance" class="boutonValider"><img src="images/valider.png" class="imageIcone" alt="icone valider"><span>Démarrer la scéance</span></button>';
        }
        // }

        if (isset($_POST['valeurJetonsIdObjectif'])) {
            $valeur = explode(".", $_POST['valeurJetonsIdObjectif']);
            if ($valeur[0] > NombreDeJetonsPlaces($valeur[1])) {
                AjouterJetonsPlaces($valeur[1]);
                ajouterJeton($valeur[1], time(), $_SESSION['idConnexion'], $valeur[0]);
            } else {
                SupprimerJetonsPlaces($valeur[1]);
            }
        }
        if (isset($_POST['redirect'])) {
            afficherObjectifsZoom($_POST['redirect']);
        } else {
            afficherObjectifsZoom($_SESSION['objectif']);
        }
        ?>
        <div id="containerRecompenses">
            <?php
            if (isset($_POST['redirect'])) {
                afficherRecompenseSelonObjectif($_POST['redirect']);
            } else {
                afficherRecompenseSelonObjectif($_SESSION['objectif']);
            }
            ?>
        </div>
    </form>
    <?php
    afficherBarresProgression($_SESSION['objectif']);
    if (champRempli(array('champSujet', 'champCorps'))) {
        ajouterMessage(
            $_POST['champSujet'],
            $_POST['champCorps'],
            time(),
            $_SESSION['objectif'],
            $_SESSION['idConnexion']
        );
    }
    faireChatObjectif();
    ?>
</body>

</html>