<?php
  session_start();
  $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');

  $query = "INSERT INTO paiements (customer_id, payment_date, status, trajet_id) VALUES (:customer_id, :payment_date, 'success', :id_trajet)";
  $stmt = $mysqlClient->prepare($query);

  // Liaison des valeurs aux marqueurs de paramètres
  // $customer_id = $_SESSION['customer_id'];
  $payment_date = date("Y-m-d H:i:s");  // Format de la date actuelle
  // $status = 'success';  // Assurez-vous de traiter les données entrantes
  $id_trajet = 1;  // Conversion en entier pour éviter les injections SQL

  $stmt->bindParam(':customer_id',  $_SESSION['customer_id'], PDO::PARAM_INT);
  $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);
  // $stmt->bindParam(':status', 'success', PDO::PARAM_STR);
  $stmt->bindParam(':id_trajet', $id_trajet, PDO::PARAM_INT);

  $stmt->execute();

?>

<!DOCTYPE html>
<html>
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
            echo "<li><a href=\"/#popular-destination\">Destinations</a></li>";
            echo "<li><a href=\"/pages/frontend/resume.php#\">Historique</a></li>";
            echo "<li><a href=\"#contact\">Contact</a></li>";
            echo "<li><a href=\"#\"><i class=\"fas fa-sign-out-alt\"></i></a></li>";
          }else{
            echo "<li><a href=\"#home\">Accueil</a></li>";
            echo "<li><a href=\"#a-propos\">à propos</a></li>";
            echo "<li><a href=\"#popular-destination\">Destinations</a></li>";
            echo "<li><a href=\"contact\">Contact</a></li>";
            echo "<li><a href=\"#=/pages/auth.php\">Authentification</a></li>";
          }
        ?>
      </ul>
    </header>

    <div class="ticket-download">
      <h3>
        Félicatation pour votre réservation.Imprimer votre reçu immédiatement et conserver le en sûreté avant et durant le voyage
      </h3>
      <hr>
      <h2>Tickets de <?php echo $_SESSION['name']  ?> </h2>
      <div class="details">
        <div>
          <h4>Passengers</h4>
        </div>
        <div>
          <h4>Destination</h4>
        </div>
        <div>
          <h4>Date</h4>
        </div>
        <div>
          <h4>Type de Billet</h4>
        </div>
      </div>

    </div>
    <div class="home-btn"style="margin:10px 0;">
      <button class="btn-reservation" id="print-button">Imprimer le ticket</button>
    </div>
    <section class="ticket-section">
      <p>
        Au cas de préoccupation; contactez-nous via ce mail : 
        <a href="mailto:orders@example.com">orders@example.com</a>.
      </p>
    </section>
  </body>
  <script>
        var print_button = document.querySelector('#print-button');
        var ticket_download = document.querySelector('.ticket-download').outerHTML;

        print_button.addEventListener('click', (e) => {
            e.stopPropagation();
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print Ticket</title>');
            printWindow.document.write('<link rel="stylesheet" href="../public/css/styl.css">'); // Lien vers votre fichier de styles CSS
            printWindow.document.write('</head><body>');
            printWindow.document.write(ticket_download);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
  </script>
</html>
