<?php

use Stripe\Terminal\Location;

    echo "DECONNEXION";
    session_start();
    session_destroy();
    header("Location:../index.php")

?>