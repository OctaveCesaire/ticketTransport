<?php
    session_start();

    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['customer_email'];
        $password = $_POST['password'];

        $stmt = $mysqlClient->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $user['email'];

        if ($user && password_verify($password, $user['pswd'])) {
            $_SESSION['customer_id'] = $user['id'];
            if($user['role_as']==='guest')
                header('Location: ../index.php');
            else{
                $_SESSION['customer_role'] = $user['role_as'];
                header('Location: ../pages/admin/dashbord.php');
            }
            exit;
        } else {
            // Authentification échouée
            header('Location: ../pages/auth.php');
            exit;
        }
    } else {
        echo "Aucune donnée reçue.";
    }
?>
