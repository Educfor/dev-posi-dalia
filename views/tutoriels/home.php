<?php

//echo "views/tutoriels/home.php";


foreach($tutos as $tuto)
{
    echo '<h2><a href="'.WEBROOT.'tutoriels/view_single/'.$tuto['id'].'">'.$tuto['nom'].'</a></h2>';
}
/*
echo '<h1>'.$tutos['id'].'</h1>';
echo '<p>'.$tutos['nom'].'</p>';
*/
?>
