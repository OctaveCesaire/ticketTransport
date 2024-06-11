<?php
session_start();

// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=localhost;dbname=gares;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérifiez si les champs requis sont remplis
if (!isset($_POST['username'], $_POST['customer_email'], $_POST['password'])) {
    exit("Veuillez remplir tous les champs");
}

$username = trim($_POST['username']);
$email = filter_var(trim($_POST['customer_email']), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);

// Vérifiez si l'e-mail est valide
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['user_exist'] = "<div style='color:red;'>Adresse e-mail invalide</div>";
    header('Location: ../pages/auth.php');
    exit;
}

// Vérifiez si l'e-mail existe déjà dans la base de données
$query = "SELECT email FROM users WHERE email = :email";
$stmt = $mysqlClient->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    // L'e-mail existe déjà
    $_SESSION['user_exist'] = "<div style='color:red;'>L'adresse e-mail existe déjà</div>";
    header('Location: ../pages/auth.php');
    exit;
} else {
    // Préparation de la requête d'insertion
    $query = "INSERT INTO users (fullname, email, pswd) VALUES (:username, :customer_email, :password)";
    $stmt = $mysqlClient->prepare($query);

    // Liaison des valeurs aux marqueurs de paramètres
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':customer_email', $email, PDO::PARAM_STR);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

    // Exécution de la requête préparée
    $stmt->execute();
    $_SESSION['customer_id'] = $mysqlClient->lastInsertId();

    header('Location: ../index.php');
    exit;
}
?>
