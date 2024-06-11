<?php
session_start();
if (isset($_SESSION['customer_id'])) {
    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    header('Location:../common/404.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RailRoost</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="shortcut icon" href="../../public/image/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../public/css/styl.css">
    <script src="../../public/js/script.js" async></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<header>
    <div class="logo">
        <a href=""><span>Rail</span> Roost</a>
    </div>
    <ul class="menu">
        <?php
        if (isset($_SESSION['customer_id'])) {
            echo "<li><a href=\"../../index.php\">Accueil</a></li>";
            echo "<li><a href=\"#\">Historique</a></li>";
            echo "<li><a href=\"./contact.php\">Contact</a></li>";
            echo "<li><a href=\"../../backend/deconnexion.php\"><i class=\"fas fa-sign-out-alt\"></i></a></li>";
        } else {
            echo "<li><a href=\"../../index.php\">Accueil</a></li>";
            echo "<li><a href=\"../../index.php#a-propos\">A Propos</a></li>";
            echo "<li><a href=\"../../index.php#popular-destination\">Destinations</a></li>";
            echo "<li><a href=\"./contact.php\">Contact</a></li>";
            echo "<li><a href=\"../auth.php\">Authentification</a></li>";
        }
        ?>
    </ul>
</header>
<div class="ticket-download" style="height: 437px;">
    <h3>Historique de vos transactions</h3>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Départs</th>
                <th>Trajets</th>
                <th>Coûts</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $resmue = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');
            $query = "SELECT paiements.id, paiements.date_de_depart, tarifs.gares_arrivee, tarifs.gares_depart, tarifs.prix, status 
                      FROM paiements 
                      JOIN tarifs ON tarifs.id = paiements.trajet_id 
                      JOIN users ON paiements.customer_id = users.id 
                      WHERE paiements.customer_id = :customer_id";
            $check = $mysqlClient->prepare($query);

            $check->bindParam(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);

            if ($check->execute()) {
                $i = 0;
                while ($elt = $check->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td style='padding: 8px;'>" . $elt['date_de_depart'] . "</td>";
                    echo "<td style='padding: 8px;'>" . $elt['gares_depart'] . " vers " . $elt['gares_arrivee'] . "</td>";
                    echo "<td style='padding: 8px;'>" . $elt['prix'] . "</td>";
                    echo "<td style='padding: 8px;'>" . $elt['status'] . "</td>";
                    if ($elt['status'] === 'success') {
                        echo "<td style='display:flex;justify-content:center;padding: 8px;'>";

                        // Form 1
                        echo "<form action='../common/details.php' method='get'>";
                        echo "<input type='hidden' name='id' value='".$elt['id']."'>";
                        echo "<button type='submit' style='border:1px solid #74C0FC; width:30px;padding:1px;margin-inline:5px;' ><i class='fas fa-eye' style='color: #74C0FC;'></i></a>";
                        echo "</form>";

                        // Form 2
                        echo "<form action='../../backend/deleteOrders.php' method='post' >";
                        echo "<input type='hidden' name='id' value='".$elt['id']."'>";
                        echo "<button type='submit' style='border: 1px solid #74C0FC;padding:1px;margin-inline:5px;width:30px'><i class='fas fa-trash' style='color: #f70000;'></i></a>";
                        echo "</form>";

                        echo "</td>";
                    }
                    echo "</tr>";

                    $i += 1;
                }
            }
            ?>
        </tbody>
    </table>
</div>
<div style="margin-top: 30px;">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/marcoprojet/pages/common/footer.php'); ?>
</div>
</body>
</html>
