CANCEL

<?php
  session_start();
  $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');

  $query = "INSERT INTO paiements (customer_id, payment_date, status, trajet_id) VALUES (:customer_id, :payment_date, 'cancel', :id_trajet)";
  $stmt = $mysqlClient->prepare($query);

  $payment_date = date("Y-m-d H:i:s");  // Format de la date actuelle
  // $id_trajet = 1;  // Conversion en entier pour éviter les injections SQL

  $stmt->bindParam(':customer_id',  $_SESSION['customer_id'], PDO::PARAM_INT);
  $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);
  $stmt->bindParam(':id_trajet', $_SESSION['id_trajet'], PDO::PARAM_INT);

  $stmt->execute();
  header('Location:./resume.php');

?>