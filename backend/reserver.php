<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['customer_id'] !== null) {
        $departure = htmlspecialchars($_POST['departure']);
        $arrival = htmlspecialchars($_POST['arrival']);
        $senior = isset($_POST['senior']) ? intval($_POST['senior']) : 0;
        $adult = isset($_POST['adult']) ? intval($_POST['adult']) : 0;
        $young = isset($_POST['young']) ? intval($_POST['young']) : 0;
        $child = isset($_POST['child']) ? intval($_POST['child']) : 0;
        
        $_SESSION['departure'] = $departure;
        $_SESSION['arrival'] = $arrival;
        $_SESSION['senior'] = $senior;
        $_SESSION['adult'] = $adult;
        $_SESSION['young'] = $young;
        $_SESSION['child'] = $child;

        header("Location:../pages/frontend/reservation.php");
    }
    elseif($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['customer_id'] === null){
        header("Location:../pages/auth.php");
    }    
    else {
        echo "Aucune donnée reçue.";
    }
    echo '<img src = "../pages/frontend/reservation.php"/>'
?>