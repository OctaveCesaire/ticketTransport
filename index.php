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
    <link rel="stylesheet" href="public/css/styl.css">
    <link rel="shortcut icon" href="public/image/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="public/js/script.js" async></script>
  </head>
<body>
  <header>
    <div class="logo">
      <a href=""><span>Rail</span> Roost</a>
      </div>
      <ul class="menu">
        <?php if(isset($_SESSION['customer_id'])) {
            echo "<li><a href=\"./index.php\">Accueil</a></li>";
            echo "<li><a href=\"./pages/frontend/resume.php\">Historique</a></li>";
            echo "<li><a href=\"./pages/frontend/contact.php\">Contact</a></li>";
            echo "<li><a href=\"./backend/deconnexion.php\"><i class=\"fas fa-sign-out-alt\"></i></a></li>";
          }else{
            echo "<li><a href=\"./index.php\">Accueil</a></li>";
            echo "<li><a href=\"./index.php#a-propos\">A Propos</a></li>";
            echo "<li><a href=\"./index.php#popular-destination\">Destinations</a></li>";
            echo "<li><a href=\"./pages/frontend/contact.php\">Contact</a></li>";
            echo "<li><a href=\"./pages/auth.php\">Authentification</a></li>";
          }
        ?>
      </ul>
  </header>

  <div id="home">
    <h2>Nous suivre</h2>
    <h4>Voyagez en Sécurité</h4>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vel commodi deserunt quasi cumque c</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe hic provident ut minus qsfshcbqj shquysvc</p>
    
    <div class="find_trip">
      <form action="./backend/reserver.php" method="post">
        <div class="select">
          <div>
            <div>
              <label for="departure">Gare de départ:</label>
            </div>
            <div>
              <select id="departure" name="departure" required onchange="disableSelectedOption()">
                <!-- Options seront ajoutées dynamiquement par JavaScript -->
                <option value="#" disabled selected></option>
                <?php 
                  $state = $mysqlClient->query("SELECT id,nom FROM gare_depart");
                  while($furn = $state->fetch()){
                    echo ' <option value = ' .$furn['id']. ' > '.$furn['nom'].'</option>';                     
                  }
                ?>
              </select>
            </div>
          </div>
          <div>
            <div>
              <label for="arrival">Gare d'arrivée:</label>
            </div>
            <div>
              <select id="arrival" name="arrival" required>
                <!-- Options seront ajoutées dynamiquement par JavaScript -->
                <option value="#" disabled selected></option>
                <?php 
                  $state = $mysqlClient->query("SELECT id,nom FROM gare_arrivee");
                  while($furn = $state->fetch()){
                    echo ' <option value = ' .$furn['id']. ' > '.$furn['nom'].'</option>';                     
                  } 
                ?>
              </select>
            </div>
          </div>
          <div>
            <div>
              <label for="starting_date">Date de départ</label>
            </div>
            <div>
              <input type="date" name="starting_date" id="starting_date" required>
            </div>

          </div>
          <a href="#" id="passengerButton">Passager</a>
        </div>
        <p id="souci" style="color: red; font-size:small;text-align:center; margin-bottom:12px"></p>
        <p id="souci_date" style="color: red; font-size:small;text-align:center; margin-bottom:12px"></p>
        <p id="souci_trajet" style="color: red; font-size:small;text-align:center; margin-bottom:12px"></p>
        <div class="modal-content">
          <div>
            <div>
              <div>
                <span class="close">&times;</span>
                <h2>Sélectionner le type de passager</h2>
                <div class="selection">
                  <div>
                      <label for="senior">Senior (60ans et +):</label>
                      <input type="number" id="senior" name="senior" placeholder="0" min="0" value="0">
                  </div>
                  <div>
                      <label for="adult">Adulte (26-50 ans):</label>
                      <input type="number" id="adult" name="adult" placeholder="0" min="0" value="0">
                  </div>
                  <div>
                      <label for="young">Jeune (18-25 ans):</label>
                      <input type="number" id="young" name="young" placeholder="0" min="0" value="0">
                  </div>
                  <div>
                      <label for="child">Enfant (0-11 ans):</label>
                      <input type="number" id="child" name="child" placeholder="0" min="0" value="0">
                  </div>
                </div>               
              </div>
            </div>
          </div>

        </div>
        <div class="home-btn">
          <input type="submit" class="btn-reservation reserver" value="Réserver Maintenant">
        </div>
      </form>
    </div>

  </div>

  <!-- Section à Propos -->
  <div id="a-propos">
    <h1 style="text-transform: uppercase; margin-top:20px">à propos</h1>
    <div class="img-desc">
      <div class="left">
        <video id="video" src="public/videos/video2.mp4" autoplay="" loop="" muted=""></video>
      </div>
      <div class="right">
        <h3>Voyagez sur nos rails pour d'autres états, d'autres vies, d'autres âmes. </h3>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Harum repudiandae numquam officiis in vel natus error aliquid.</p>
        <a href="#">Lire plus</a>
      </div>
    </div>
  </div>

  <!-- Section Destination -->
  <div id="popular-destination">
    <h1 class="title">Destinations populaires</h1>
    <div class="content">
      <!-- box -->
      <div class="box">
        <img src="public/image/img4.jpeg">
        <div class="content">
          <div>
            <h4>Paris</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/lyon.jpg">
        <div class="content">
          <div>
            <h4>Lyon Part-Dieu</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/marseille.jpg">
        <div class="content">
          <div>
            <h4>Marseille</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/monaco.jpg">
        <div class="content">
          <div>
            <h4>Monaco</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/nice.jpg">
        <div class="content">
          <div>
            <h4>Nice</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
        <!-- box -->
        <div class="box">
          <img src="public/image/deauville.jpg">
          <div class="content">
            <div>
              <h4>Deauville</h4>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
              <a href="#">Lire plus</a>
            </div>
          </div>
        </div>
        <!-- box -->
    </div>
  </div>
  <div id="popular-destination">
    <div class="content">
      <!-- box -->
      <div class="box">
        <img src="public/image/img4.jpeg">
        <div class="content">
          <div>
            <h4>Paris</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/lyon.jpg">
        <div class="content">
          <div>
            <h4>Lyon Part-Dieu</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/marseille.jpg">
        <div class="content">
          <div>
            <h4>Marseille</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/monaco.jpg">
        <div class="content">
          <div>
            <h4>Monaco</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
       <!-- box -->
       <div class="box">
        <img src="public/image/nice.jpg">
        <div class="content">
          <div>
            <h4>Nice</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
            <a href="#">Lire plus</a>
          </div>
        </div>
      </div>
      <!-- box -->
        <!-- box -->
        <div class="box">
          <img src="public/image/deauville.jpg">
          <div class="content">
            <div>
              <h4>Deauville</h4>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis voluptatum ex hic impedit quis doloremque .</p>
              <a href="#">Lire plus</a>
            </div>
          </div>
        </div>
        <!-- box -->
    </div>
  </div>

  <!-- <div id="Pourquoi-voyager" >
    <h1 class="titre" >Pourquoi voyager avec Rail <span>Roost</span> ?</h1>
  </div> -->
  <?php include('./pages/common/footer.php') ?>
  <script>
    var btn_reserver = document.querySelector('.reserver');
    btn_reserver.addEventListener('click', (e) => {
      var selection = document.querySelectorAll('.selection input[type="number"]');
      let nbr_Correct = 0;
      let nbreSelect_Correct = 0;
      let date_Bon = true;
      var currentDate = new Date();
      var Date_Pris = new Date(document.getElementById('starting_date').value);
      var gare_Depart = document.getElementById('departure').value;
      var gare_Arrivee = document.getElementById('arrival').value;
      let bon_trajet = true;
      if(gare_Arrivee === gare_Depart){
        bon_trajet = false;
      }

      if(Date_Pris < currentDate){
        date_Bon = false;
      }
      // Vérifiez les inputs de type number
      selection.forEach(elt => {
        if (parseInt(elt.value) > 0) {
          nbr_Correct += 1;
        }
      });

      // Vérifiez les options sélectionnées dans les selects
      var slt = document.querySelectorAll('select');
      slt.forEach(select => {
        if (select.value !== '#') {
          nbreSelect_Correct += 1;
        }
      });

      if ((nbr_Correct === 0 || nbreSelect_Correct !== 2) ) {
        document.getElementById('souci').innerText = "Vérifier vos informations: Gare de départ, arrivée et les passagers.";
        e.preventDefault();
        e.stopPropagation();
      }
      if(!date_Bon){
        document.getElementById('souci_date').innerText = "Date de départ incorrect";
        e.preventDefault();
        e.stopPropagation();
      }
      if(!bon_trajet){
        document.getElementById('souci_trajet').innerText = "Trajet incorrect";
        e.preventDefault();
        e.stopPropagation();
      }
    });
  </script>
</body>
</html>