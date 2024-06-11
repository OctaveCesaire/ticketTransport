<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RailRoost</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="shortcut icon" href="../public/image/favicon.png" type="image/x-icon">
        <link rel="stylesheet" href="../public/css/styl.css">
        <script src="../public/js/script.js" async></script>
        <script src="https://js.stripe.com/v3/"></script> <!-- Script pour la gestion de stripe -->
    </head>
    <body>
    <header>
        <div class="logo">
        <a href=""><span>Rail</span> Roost</a>
      </div>
      <ul class="menu">
        <?php if(isset($_SESSION['customer_id'])) {
            echo "<li><a href=\"../index.php\">Accueil</a></li>";
            echo "<li><a href=\"./frontend/resume.php\">Historique</a></li>";
            echo "<li><a href=\"./frontend/contact.php\">Contact</a></li>";
            echo "<li><a href=\"../backend/deconnexion.php\"><i class=\"fas fa-sign-out-alt\"></i></a></li>";
          }else{
            echo "<li><a href=\"../index.php\">Accueil</a></li>";
            echo "<li><a href=\"../index.php#a-propos\">A Propos</a></li>";
            echo "<li><a href=\"../index.php#popular-destination\">Destinations</a></li>";
            echo "<li><a href=\"./frontend/contact.php\">Contact</a></li>";
            echo "<li><a href=\"#\">Authentification</a></li>";
          }
        ?>
      </ul>
    </header>

		<?php
           
            if(!isset($_SESSION['customer_id'])){
                echo "<div class='auth'>
                <div class='login'>
                    <h1>Inscription</h1>
                    <form action='../backend/inscription.php' method='post'>
                    <label for='username'>
                            <i class='fas fa-user'></i>
                        </label>
                        <input type='text' name='username' placeholder='Nom' id='username' required>
                        <label for='email'>
                            <i class='fas fa-envelope'></i>
                        </label>
                        <input type='email' name='customer_email' placeholder='Email' id='email' required>
                        <label for='password'>
                            <i class='fas fa-lock'></i>
                        </label>
                        <input type='password' name='password' placeholder='Mot de passe' id='password' required>
                        ";
                        ;
                        if(isset($_SESSION['user_exist'])){
                            echo  "<div>".$_SESSION['user_exist']."</div>";
                            unset($_SESSION['user_exist']);
                        };
                echo "
                        <input type='submit' value=\"S'inscrire\">
                    </form>
                </div>
                <div class='login'>
                    <h1>Connexion</h1>
                    <form action='../backend/authenticate.php' method='post'>
                        <label for='username'>
                            <i class='fas fa-user'></i>
                        </label>
                        <input type='email' name='customer_email' placeholder='Email' id='email' required>
                        <label for='password'>
                            <i class='fas fa-lock'></i>
                        </label>
                        <input type='password' name='password' placeholder='Mot de passe' id='password' required>
                        <input type='submit' value='Connexion'>
                    </form>
                </div>
            </div>";
            }
            else{
                echo " <div class='login'><div style =\"background-color: #100;\">Vous êtes actuellement connecté.</div></div>";
            }
        ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/marcoprojet/pages/common/footer.php'); ?>

	</body>
</html>