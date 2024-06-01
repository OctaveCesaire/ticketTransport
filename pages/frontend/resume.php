<?php session_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RailRoost</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href="../../public/css/styl.css">
        <script src="../../public/js/script.js" async></script>        <script src="https://js.stripe.com/v3/"></script> <!-- Script pour la gestion de stripe -->
    </head>
    <body>
        <header>
            <div class="logo">
              <a href="/"><span>Rail</span> Roost</a>
            </div>
            <ul class="menu">
              <?php if($_SESSION['customer_id'] !== null) {
                    echo "<li><a href=\"/\">Accueil</a></li>";
                    echo "<li><a href=\"#\">Historique</a></li>";
                    // echo "<li><a href=\"#contact\">Profile</a></li>";
                    echo "<li><a href=\"/backend/deconnexion.php\"><i class=\"fas fa-sign-out-alt\"></i></a></li>";
                }else{
                    echo "<li><a href=\"#home\">Accueil</a></li>";
                    echo "<li><a href=\"#a-propos\">à propos</a></li>";
                    echo "<li><a href=\"#popular-destination\">Destinations</a></li>";
                    echo "<li><a href=\"#contact\">Contact</a></li>";
                    echo "<li><a href=\"/pages/auth.php\">Authentification</a></li>";
                }
                ?>
            </ul>
        </header>

        <div class="ticket-download">
            <h3>Historique de vos transactions</h3>
            <hr>
            <table>
                <thead>
                    <th>#</th>
                    <th>Dates & Heures</th>
                    <th>Destinations</th>
                    <th>Départs</th>
                    <th>Coûts</th>
                    <th>Status</th>
                </thead>
                <tbody>
                <?php
                    $resmue =  $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8','root','');
                    $query = "SELECT  payment_date,tarifs.gares_arrivee , tarifs.gares_depart,tarifs.prix,status FROM paiements JOIN tarifs ON tarifs.id = paiements.id JOIN users WHERE paiements.customer_id=:customer_id";
                    // $query = "SELECT payment_date,tarifs.gares_arrivee , tarifs.gares_depart,tarifs.prix FROM paiements JOIN tarifs WHERE tarifs.id = :id_trajet";
                    $check = $mysqlClient->prepare($query);

                    $check->bindParam(':customer_id',$_SESSION['customer_id'],PDO::PARAM_INT);

                    if($check->execute()){
                        $i =0;
                        while ($elt = $check->fetch(PDO::FETCH_ASSOC)) { 
                            echo "<tr>";
                            echo "<td>". ($i+1)."</td>";
                            echo " <td>".$elt['payment_date']."</td>";
                            echo " <td>".$elt['gares_arrivee']."</td>";
                            echo " <td>".$elt['gares_depart']."</td>";
                            echo " <td>".$elt['prix']."</td>";
                            echo " <td>".$elt['status']."</td>";
                            echo "</tr>";
                            $i+=1;
                        }
                    }
                ?>
                    
                </tbody>
            </table>

            <div class="details"></div>


        </div>
    
    </body>
</html>