<?php
    session_start();
    try
    {
      $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8',
        'root','');
    }
    catch (Exception $e)
    {
      die('Erreur : ' . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RailRoost</title>
    <link rel="stylesheet" href="../../public/css/styl.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="../../public/js/script.js" async></script>
    <script src="https://js.stripe.com/v3/"></script> <!-- Script pour la gestion de stripe -->
</head>
<body>
    <header>
        <div class="logo">
            <a href="/"><span>Rail</span> Roost</a>
        </div>
        <ul class="menu">
            <?php if($_SESSION['customer_id'] !== null) {
                echo "<li><a href=\"/\">Accueil</a></li>";
                echo "<li><a href=\"/pages/frontend/resume.php\">Historique</a></li>";
                echo "<li><a href=\"/backend/deconnexion.php\"><i class=\"fas fa-sign-out-alt\"></i></a></li>";
            }else{
                echo "<li><a href=\"/#home\">Accueil</a></li>";
                echo "<li><a href=\"/#a-propos\">à propos</a></li>";
                echo "<li><a href=\"/#popular-destination\">Destinations</a></li>";
                echo "<li><a href=\"/#contact\">Contact</a></li>";
                echo "<li><a href=\"/pages/auth.php\">Authentification</a></li>";
            }
            ?>
        </ul>
    </header>
    <div  id="form-reservation">
        <form action="../../backend/checkout.php" method="post" id="payment-form">
            <div>
                <fieldset>
                    <legend> Direction </legend>
                    <div class="reservation">
                        <div>
                            <label for="depart">De :</label>
                            <div>
                                <?php
                                    if (isset($_SESSION['departure'])) {
                                        $departureId = intval($_SESSION['departure']);
                                        $stmt = $mysqlClient->prepare('SELECT nom FROM gare_depart WHERE id = :id');
                                        $stmt->bindParam(':id', $departureId, PDO::PARAM_INT);
                                        $stmt->execute();
                                        $data = $stmt->fetch(PDO::FETCH_ASSOC);
                                        if ($data) {
                                            $gareNom = htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8');
                                            echo "<input type='text' name='departure' value='{$gareNom}' readonly>";
                                        } else {
                                            echo "<input type='text' name='departure' value='Gare non trouvée' readonly>";
                                        }
                                    } else {
                                        echo "<input type='text' name='departure' value='Aucune gare sélectionnée' readonly>";
                                    }
                                ?>
                            </div>
                        </div>
                        <div>
                            <label for="arrival"> A :</label>
                            <div>
                                <?php
                                    if (isset($_SESSION['arrival'])) {
                                        $arrivalId = intval($_SESSION['arrival']);
                                        $stmt = $mysqlClient->prepare('SELECT nom FROM gare_arrivee WHERE id = :id');
                                        $stmt->bindParam(':id', $arrivalId, PDO::PARAM_INT);
                                        $stmt->execute();
                                        $data = $stmt->fetch(PDO::FETCH_ASSOC);
                                        if ($data) {
                                            $gareNomArrive = htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8');
                                            echo "<input type='text' name='arrival' value='{$gareNomArrive}' readonly>";
                                        } else {
                                            echo "<input type='text' name='arrival' value='Gare non trouvée' readonly>";
                                        }
                                    } else {
                                        echo "<input type='text' name='arrival' value='Aucune gare sélectionnée' readonly>";
                                    }
                                ?>
                            </div>
                        </div>           
                        <div>
                            <label for="tarif"> Prix :</label>
                            <div>
                                <?php
                                    $query = "SELECT id,prix FROM tarifs WHERE gares_depart = :depart AND gares_arrivee = :arrivee";
                                    $stmt = $mysqlClient->prepare($query);

                                    $stmt->bindParam(':depart', $gareNom, PDO::PARAM_STR);
                                    $stmt->bindParam(':arrivee', $gareNomArrive, PDO::PARAM_STR);

                                    if ($stmt->execute()) {
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $_SESSION['id_trajet'] = $row['id'];
                                        if ($row) {
                                            $prix = $row['prix'];
                                            echo "<input type='text' name='tarif' readonly value='$prix'><span>&#8364;</span>";
                                        } else {
                                            echo "Aucun tarif trouvé pour ce trajet.";
                                        }
                                    } else {
                                        echo "Erreur lors de l'exécution de la requête.";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend> Pour  </legend>
                    <div class="reservation">
                        <div>
                            <label for="senior">Senior</label>
                            <div>
                                <?php
                                    if (isset($_SESSION['senior'])) {
                                        $seniorId = intval($_SESSION['senior']);
                                        echo "<input type='number' name='senior' value='{$seniorId}' readonly>";  
                                    }
                                ?>
                            </div>
                            <div>
                                <?php
                                    if(isset($_SESSION['senior'])){
                                        $i = intval($_SESSION['senior']);
                                        while ($i >= 1){
                                            echo '<input required type="text" name="senior" value="" placeholder="Nom & Prenom">';
                                            $i -= 1;
                                        }
                                    }
                                ?>
                            </div>
                        </div>     
                        <div>
                            <label for="adult">Adulte</label>
                            <div>
                                <?php
                                    if (isset($_SESSION['adult'])) {
                                        $seniorId = intval($_SESSION['adult']);
                                        echo "<input type='number' name='adult' value='{$seniorId}' readonly>";
                                    }
                                ?>
                            </div>
                            <div>
                                <?php
                                    if(isset($_SESSION['adult'])){
                                        $i = intval($_SESSION['adult']);
                                        while ($i >= 1){
                                            echo '<input required type="text" name="adult" value="" placeholder="Nom & Prenom">';
                                            $i -= 1;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div>
                            <label for="child">Enfant</label>
                            <div>
                                <?php
                                    if (isset($_SESSION['child'])) {
                                        $seniorId = intval($_SESSION['child']);
                                        echo "<input type='number' name='child' value='{$seniorId}' readonly>";
                                    }
                                ?>
                            </div>
                            <div>
                                <?php
                                    if(isset($_SESSION['child'])){
                                        $i = intval($_SESSION['child']);
                                        while ($i >= 1){
                                            echo '<input required type="text" name="child" value="" placeholder="Nom & Prenom">';
                                            $i -= 1;
                                        }
                                    }
                                ?>
                            </div>
                        </div>        
                        <div>
                            <label for="young">Jeune</label>
                            <div>
                                <?php
                                    if (isset($_SESSION['young'])) {
                                        $seniorId = intval($_SESSION['young']);
                                        echo "<input type='number' name='young' value='{$seniorId}' readonly>";
                                    }
                                ?>
                            </div>
                            <div>
                                <?php
                                    if(isset($_SESSION['young'])){
                                        $i = intval($_SESSION['young']);
                                        while ($i >= 1){
                                            echo '<input required type="text" name="young" value="" placeholder="Nom & Prenom">';
                                            $i -= 1;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend> Cordonnées </legend>
                    <div class="reservation">
                        <div>
                            <label for="fname">Nom :</label>
                            <div>
                                <input required type="text" name="fname">
                            </div>
                        </div>
                        <div>
                            <label for="lname"> Prenom :</label>
                            <div>
                                <input required type="text" name="lname">
                            </div>
                        </div>
                        <div>
                            <label for="email"> Email :</label>
                            <div>
                                <input type="email" required name="email" value="">
                            </div>
                        </div>            
                    </div>
            
                </fieldset>
            </div>
            <div class="home-btn">
                <input type="submit" class="btn-reservation" style="margin-top:10px" value="Réserver Maintenant">
            </div>
        </form>    
    </div>
</body>
</html>

    