<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location:../common/404.php');
    exit;
}

try {
    $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');

    $user = $mysqlClient->prepare("SELECT fullname, email FROM users WHERE id = :id");
    $user->bindParam(':id', $_SESSION['customer_id'], PDO::PARAM_INT);
    $user->execute();
    $furn = $user->fetch(PDO::FETCH_ASSOC);

    if (!$furn) {
        throw new Exception('Utilisateur non identifié.');
    }

    $departureId = isset($_SESSION['departure']) ? intval($_SESSION['departure']) : null;
    $arrivalId = isset($_SESSION['arrival']) ? intval($_SESSION['arrival']) : null;

    if ($departureId && $arrivalId) {
        $stmt = $mysqlClient->prepare('SELECT nom FROM gare_depart WHERE id = :id');
        $stmt->bindParam(':id', $departureId, PDO::PARAM_INT);
        $stmt->execute();
        $departureData = $stmt->fetch(PDO::FETCH_ASSOC);
        $gareNom = $departureData ? $departureData['nom'] : 'Gare non trouvée';

        $stmt = $mysqlClient->prepare('SELECT nom FROM gare_arrivee WHERE id = :id');
        $stmt->bindParam(':id', $arrivalId, PDO::PARAM_INT);
        $stmt->execute();
        $arrivalData = $stmt->fetch(PDO::FETCH_ASSOC);
        $gareNomArrive = $arrivalData ? $arrivalData['nom'] : 'Gare non trouvée';

        $query = "SELECT id, prix FROM tarifs WHERE gares_depart = :depart AND gares_arrivee = :arrivee";
        $stmt = $mysqlClient->prepare($query);
        $stmt->bindParam(':depart', $gareNom, PDO::PARAM_STR);
        $stmt->bindParam(':arrivee', $gareNomArrive, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $_SESSION['id_trajet'] = $row['id'];
            $prix = $row['prix'];
        } else {
            $prix = 0;
            $error = "Aucun tarif trouvé pour ce trajet.";
        }
    } else {
        $gareNom = 'Aucune gare sélectionnée';
        $gareNomArrive = 'Aucune gare sélectionnée';
        $prix = null;
        $error = "Sélectionnez les gares de départ et d'arrivée.";
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RailRoost</title>
    <link rel="shortcut icon" href="../../public/image/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../public/css/styl.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="../../public/js/script.js" async></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.querySelector("input[name='starting_date']");
            dateInput.setAttribute('min', today);

            dateInput.addEventListener('change', function() {
                if (this.value < today) {
                    alert("La date de départ doit être supérieure ou égale à la date d'aujourd'hui.");
                    this.value = today;
                }
            });
        });
    </script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="/"><span>Rail</span> Roost</a>
        </div>
        <ul class="menu">
            <?php if (isset($_SESSION['customer_id'])): ?>
                <li><a href="/">Accueil</a></li>
                <li><a href="./resume.php">Historique</a></li>
                <li><a href="./contact.php">Contact</a></li>
                <li><a href="../../backend/deconnexion.php"><i class="fas fa-sign-out-alt"></i></a></li>
            <?php else: ?>
                <li><a href="../../index.php">Accueil</a></li>
                <li><a href="../../index.php#a-propos">A Propos</a></li>
                <li><a href="/#popular-destination">Destinations</a></li>
                <li><a href="./contact.php">Contact</a></li>
                <li><a href="../auth.php">Authentification</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <div id="form-reservation">
        <form action="../../backend/checkout.php" method="post" id="payment-form">
            <div>
                <fieldset>
                    <legend>Direction & date</legend>
                    <div class="reservation">
                        <div>
                            <label for="depart">De :</label>
                            <div>
                                <?php
                                echo "<input type='text' name='departure' value='" . htmlspecialchars($gareNom, ENT_QUOTES, 'UTF-8') . "' readonly>";
                                ?>
                            </div>
                        </div>
                        <div>
                            <label for="arrival"> A :</label>
                            <div>
                                <?php
                                echo "<input type='text' name='arrival' value='" . htmlspecialchars($gareNomArrive, ENT_QUOTES, 'UTF-8') . "' readonly>";
                                ?>
                            </div>
                        </div>
                        <div>
                            <label for="starting_date"> Date du départ :</label>
                            <div>
                                <?php
                                $starting_date = isset($_SESSION['starting_date']) ? $_SESSION['starting_date'] : '';
                                echo "<input type='date' name='starting_date' value='$starting_date' readonly>";
                                ?>
                            </div>
                        </div>
                        <div>
                            <label for="tarif"> Prix :</label>
                            <div>
                                <?php
                                if (isset($prix)) {
                                    echo "<input type='number' name='tarif' readonly value='$prix'><span>&#8364;</span>";
                                } else {
                                    echo $error;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend>Pour</legend>
                    <div class="reservation">
                        <?php
                        $categories = ['senior' => 'Senior', 'adult' => 'Adulte', 'child' => 'Enfant', 'young' => 'Jeune'];
                        foreach ($categories as $key => $label) {
                            if (isset($_SESSION[$key])) {
                                $count = intval($_SESSION[$key]);
                                echo "<div><label for='$key'>$label</label>";
                                echo "<div><input type='number' value='$count' readonly></div>";
                                echo "<div>";
                                for ($i = 1; $i <= $count; $i++) {
                                    echo "<input required type='text' name='{$key}{$i}' value='' placeholder='Nom & Prenom' style='width:90%;'>";
                                }
                                echo "</div></div>";
                            }
                        }
                        ?>
                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend>Coordonnées</legend>
                    <div class="reservation">
                        <div>
                            <label for="fullname">Nom Complet :</label>
                            <div>
                                <?php echo "<input required type='text' name='fullname' value='" . htmlspecialchars($furn['fullname'], ENT_QUOTES, 'UTF-8') . "' readonly>"; ?>
                            </div>
                        </div>
                        <div>
                            <label for="email">Email :</label>
                            <div>
                                <?php echo "<input type='email' required name='email' value='" . htmlspecialchars($furn['email'], ENT_QUOTES, 'UTF-8') . "' readonly>"; ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend>Type de pass</legend>
                    <div class="reservation">
                        <div>
                            <input type="radio" required name="PassType" id="bussiness" value="Bussiness Classe">
                            <label for="bussiness">Bussiness Classe</label>   
                        </div>
                        <div>
                            <input type="radio" required name="PassType" id="EConomie" value="Classe Economie">
                            <label for="EConomie">Classe Economie</label>   
                        </div>
                        <div>
                            <input type="radio" required name="PassType" id="Premium" value="Premium Classe">
                            <label for="Premium">Premium Classe</label>   
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="home-btn">
                <input type="submit" class="btn-reservation" style="margin-top:10px" value="Réserver Maintenant">
            </div>
        </form>
    </div>

    <footer style="margin-top: 70px;">
        <p>Réalisé par <span>....</span>| Tous Droits Réservés.</p>
    </footer>
</body>
</html>
