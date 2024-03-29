<?php
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
    header("Location:../index.php?view=lobby");
    die("");
}
include("templates/chat.php");
include("header_brainsto.php");


// seul les utilisateurs connectés peuvent se rendre sur join
if( !($idUser = estConnecte()) ){
    header("Location:".dirname($_SERVER[PHP_SELF])."/index.php?view=accueil");
    die("");
}



?>

<link rel="stylesheet" href="css/cssCommun.css">


<style>


    /************************* CSS DU LOBBY *************************************/

    #lobby *{
        box-sizing: border-box;
    }

    #lobby{
        position:absolute;
        right:300px;
        left:0px;
        margin:0px;
        top:120px;
    }

    #lobby h3{
        display:inline-block;
        margin:5px;
        vertical-align: top;
    }

    #lobby p{
        display:inline-block;
        margin:5px;
        vertical-align: top;
    }

    #lobby #hautLobby::after{
        content: "";
        clear: both;
        display: table;
       }

    #lobby #hautLobby{
        margin:10px;
    }

    #lobby #infoBrainsto{
        width:60%;
        /*background-color: yellow;*/
    }

    #lobby .column{
        float:left;
    }

    #lobby + div{
        width:40%;
    }
    #lobby #formMaster{
        border-radius: 10px;
        border:1px solid white;
        padding:10px;
        margin-left:20px;

    }


    /* This is to remove the arrow of select element in IE */
    select{
        color: #ED7D31;
        padding:5px 10px;
        background-color: white;
        border:none;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        text-align-last:center;
    }

    #lobby .selectList:hover{
        cursor:pointer;
    }

    #lobby #divLaunchBrainsto{
        margin-top:15px;
        margin-bottom:10px;
        width: 100%;
        text-align:center;
    }


    #lobby #divParticipants{
        text-align:center;
        margin:10px auto 0 auto;
        /*border:1px solid white;*/
        /*border-radius:10px;*/
        width:70%;

        /*border-top:1px solid white;*/
    }

    #lobby #divParticipants h3{
        font-size:1.5em;
        margin-top:20px;
    }

    #lobby #divParticipants ul {
        padding:0;
    }

    #lobby #divParticipants li{
        margin:10px;
        display:inline-block;

        padding:5px 10px;
        border:1px solid white;
        border-radius: 5px;
        font-weight: bold;
    }

    #lobby #divParticipants p {
        float:left;
    }

    #lobby #divParticipants div{
        float:left;
        display:inline-block;
        background-color: darkred;
        color: #ED7D31;
        width:20px;
        height: 20px;
        border-radius: 30px;

        border:2px solid white;

        position:relative;
        top:3px;
    }


    #lobby #ready{
        margin-top:20px;
        text-align:center;
    }

    #rejoindreFirstStep{
        display: none;
    }

</style>

<!-- RECUPARATION DES INFOS DU BRAINSTO DANS LA SESSION -->
<?php //on récupère les infos du brainsto par la session
$idBrainsto = $_SESSION["idBrainstoCourant"];

$titreBrainsto = getChamp('br_titre', 'brainstorm', 'br_id', $idBrainsto);
$descriptionBrainsto = getChamp('br_description', 'brainstorm', 'br_id', $idBrainsto);
$idMasterBrainsto = getChamp('br_master_id', 'brainstorm', 'br_id', $idBrainsto);
$nomMaster = getChamp('user_username', 'user', 'user_id', $idMasterBrainsto);
$isMaster = isMaster($idBrainsto, $idUser);


?>
<!-- ------------ FIN --------- -->



<script src="js/jquery-3.4.1.js"></script>

<script>//on désactive les fonctionnalités du master si on est pas master


    $(document).ready(function() {

        if(!<?php echo $isMaster; ?>){
            $("#launchBrainsto").css('display', 'none');
            $(".selectList").prop('disabled', true);
        }

        timeoutLobby();


        $("#btnReady").click(function() {
                console.log("click");
                $.ajax({
                    "url": "dataProvider.php",
                    "data": {variable:"ready"},
                    "type": "GET",
                    "success": function(){
                        console.log("ok j'ai cliqué sur ready");
                    },
                    "error": function () {
                        console.log("erreur lors de la maj de ready");
                    }
                });
                $("#contenuMessage").val("");
            }
        );


    });

    function timeoutLobby(){
        setTimeout(function (){
            $.ajax({"url":"dataProvider.php",
                "data":{variable:"majLobby",isMaster:<?php echo $isMaster;?>,nbTour:$("#nbTour").val(),tpsTour:$("#tpsTour").val(),tpsRelecture:$("#tpsRelecture").val()},
                "type":"GET",
                "success":function(donnees){
                    var oRep = JSON.parse(donnees);
                    // console.log(oRep);
                    $("#participants").html(oRep.recupParticipant);
                    $("#nbTour").val(oRep.nbTour);
                    $("#tpsTour").val(oRep.tpsTour);
                    $("#tpsRelecture").val(oRep.tpsRelecture);
                    // si le master a appuyé sur Lancer le brainstorm
                    if(oRep.brainstoLance)
                        document.getElementById("rejoindreFirstStep").click();
                },
                "error":function(){
                    console.log("erreur lors du chargement des infos dans lobby");
                    }
                });
            timeoutLobby();
        },1000);
    }




</script>


<div id="lobby">

    <div id="hautLobby">

        <div id="infoBrainsto" class="column">

            <h3> Nom du Master : </h3>
            <p id="nomMaster"><?php echo $nomMaster ?></p>

            <br>

            <h3>Description du Brainsto :</h3>

            <br>


            <p id="descriptionBrainsto">
                <?php echo $descriptionBrainsto ?>
            </p>

        </div>


        <form action="controleur.php" method="GET">

            <div class="column">

                <div id="formMaster" >

                    <h3>Nombre de tours :</h3>
                    <select id="nbTour" class="selectList" size="1" name="nbTours" >
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>

                    <br />

                    <h3>Temps par tour (s):</h3>
                    <select id="tpsTour" class="selectList" size="1" name="tpsTour" >
                        <option>20</option>
                        <option>30</option>
                        <option>60</option>
                        <option>120</option>

                    </select>

                    <br />

                    <h3>Temps de relecture (min):</h3>
                    <select id="tpsRelecture" class="selectList " size="1" name="tpsRelecture">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>10</option>
                    </select>

                    <br />



                </div>

                <div id="divLaunchBrainsto">
                    <input id="launchBrainsto" class="button" value="Lancer le Brainsto !" type="button" />
                </div>

                  <script>
                    $("#launchBrainsto").click(function() {
                            console.log("click");
                            $.ajax({
                                "url": "dataProvider.php",
                                "data": {variable:"lancerBrainsto"},
                                "type": "GET",
                                "success": function(){
                                    console.log("ok j'ai cliqué");
                                },
                                "error": function () {
                                    console.log("erreur lors du lancement");
                                }
                            });

                        }
                    );
                </script>
            </div>

        </form>

    </div>


    <div id="divParticipants">

        <h3>Participants</h3>

        <ul id="participants">
        </ul>

    </div>

    <div id="ready">
            <button id="btnReady" class="button">I'm Ready !</button>
    </div>


    <form action="controleur.php" method="GET">
        <input id="rejoindreFirstStep" type="submit" name="action" value="goToFirstStep"  />
    </form>

</div>

