<?php
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
    header("Location:../index.php?view=accueil");
    die("");
}

?>
<link rel="stylesheet" href="css/cssCommun.css">


<style>

    *{
        box-sizing: border-box;
    }

    /************************************************************/

    h3{
        display: inline-block;
    }


    p{
        display:inline-block;
    }

    #lienCreerUser{
        text-decoration: underline;
    }

    #lienCreerUser:hover{
        cursor:pointer;
    }


    /************************************************************/

    #hautPage{
        border-bottom:1px solid white;
        padding-left:4%;
        padding-right:4%;
        padding-bottom:25px

    }


    #hautPage::after{
        content: "";
        clear: both;
        display: table;
    }

    #descriptionSite{

        float:left;
        width:55%;
        height: 360px;
        min-width:550px;
        padding-right:4%;
        border-right:1px solid white;
        margin-bottom:50px;
    }

    img{
        width:100%;
        padding-right:5%;
        padding-left:5%;
    }


    #formLogin{
        float:left;
        width:40%;
        min-width:450px;
        text-align: center;
        margin-left:4%;
    }

    #formLogin .button{
        margin-top:20px;
    }

    #errone{
        color:#444444;
        font-weight: bold;
        display:none;
    }

    #formLoginCreate{

        margin-auto;
        text-align: center;
        padding-bottom:150px;
        padding-top:120px;
        background-color: #444;
        display:none;

    }

    #formLoginCreate .button{
        margin-top:20px;
        color:#444444;
    }

    #pseudoDejaExistant{
        display:none
    }

    #mauvaiseCorrespondance{
        display:none;
    }

    #reussi{
        display:none;
    }

</style>

<script src="js/jquery-3.4.1.js"></script>

<script>

    function showLoginForm() {
        $("#formLoginCreate").css("display","block");
        $('html, body').animate({
            scrollTop: $("#formLoginCreate").offset().top
        }, 1500);

    }


</script>


<h1 id="titre">BrainCards</h1>


<div id="hautPage">


    <div id="descriptionSite" class="column">

        <p>BrainCards est un site de Brainstorming à Distance. Il permet à l’organisateur du Brainstorming (le
            « Master » ) de faire respecter les différents temps du Brainstorming. Il permet également d’afficher les
            traditionnels « Post-it » sous une forme différente, plus complète et plus à même d’alimenter
            l’imagination des différents participant.</p>

        <img src=res/imagesAccueil.jpg>

    </div>


    <div id="formLogin" class="column">


        <h2>Connectez-vous !</h2>


        <div>
            <form action="controleur.php" method="GET">

                <h3>Pseudo :</h3>
                <input class="textInput" type="text" name="login" /><br />

                <h3>Password :</h3>
                <input class="textInput" type="password" name="passe" /><br />

                <input class="button" type="submit" name="action" value="connexion" />
            </form>
        </div>

        <p>Pas encore inscrit ? </p>

        <!-- Cliquez ici n'est en fait pas un lien, on va juste afficher ou non le form
        de création d'un utilisateur. Le lien n'est d'ailleurs pas join du coup-->
        <p id="lienCreerUser" onclick="showLoginForm();">Cliquez ici</p>

        <br>

        <p id="errone">Pseudo ou mot de passe non valide</p>

    </div>

</div>





<div id="formLoginCreate">

    <div>
        <h2>Inscrivez-vous !</h2>

        <form action="controleur.php" method="GET">

            <h3>Pseudo :</h3>
            <input class="textInput" type="text" name="login" /><br />

            <h3>Password :</h3>
            <input class="textInput"  type="password" name="passe" /><br />

            <h3>Confirmation du password :</h3>
            <input class="textInput"  type="password" name="passe2" /><br />

            <input class="button" type="submit" name="action" value="creationCompte" />
        </form>
    </div>

    <p id="mauvaiseCorrespondance">Les deux mots de passe ne correspondent pas</p>
    <p id="pseudoDejaExistant">Le pseudo est déjà pris</p>
    <p id="reussi">Votre compte a bien été créé ! Vous pouvez désormais vous connecter.</p>


</div>




