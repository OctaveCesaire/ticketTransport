<?php
    session_start();
    if(isset($_SESSION['customer_id'])) {
        try{
            $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');
        
            $query = "INSERT INTO paiements (customer_id, payment_date, status, trajet_id,date_de_depart) VALUES (:customer_id, :payment_date, 'success', :id_trajet,:starting_date)";
            $stmt = $mysqlClient->prepare($query);
        
            // echo $_SESSION['starting_date'];
            $payment_date = date("Y-m-d H:i:s");
            $stmt->bindParam(':customer_id',  $_SESSION['customer_id'], PDO::PARAM_INT);
            $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);
            $stmt->bindParam(':id_trajet', $_SESSION['id_trajet'], PDO::PARAM_INT);
            $starting_date = strtotime($_SESSION['starting_date']);
            $stmt->bindParam(':starting_date', date("Y-m-d", $starting_date), PDO::PARAM_STR);
            $stmt->execute();
            // Dernier paiement de l'utilsateur
            $lastPaymentId = $mysqlClient->lastInsertId();
            // Liste des passenger
            $passenger = "";
            if(isset($_SESSION['collect'])){
                foreach($_SESSION['collect'] as $type=>$val){
                    if($type!=='departure' && $type!=='arrival' && $type!=='starting_date' && $type!=='tarif'&& $type!=='fullname'&& $type!=='email'&& $type!=='PassType' )
                        $passenger .= $val;
                }
            }
            // Création de la commande [Table : ORDERS]
            $req = $mysqlClient->prepare("INSERT INTO orders (passenger, paiement_id) VALUES (:passenger, :paiement_id)");
            $req->bindParam(':passenger', $passenger, PDO::PARAM_STR);
            $req->bindParam(':paiement_id', $lastPaymentId, PDO::PARAM_INT);
            $req->execute();

            echo "Paiement et commande insérer avec succès";
            header('Location:./resume.php');
        }catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

    }else{
        echo 
        header('Location:../common/404.php');
    }
?>