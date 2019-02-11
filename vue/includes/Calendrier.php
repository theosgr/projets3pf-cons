<?php

$jour = date("w"); // numéro du jour actuel

if (isset($_GET['jour']))
{
    $jour = intval($_GET['jour']);
}
 
$jourTexte = array('', 1 =>'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'); 
$plageHoraire = array(1 => 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18);

echo '<table border="1" align="center">';

for($k = 0; $k < 8; $k++) // ligne principale du tableau
{
	if($k==0) { // 1ère ligne - une case vide
		echo '<th>'.$jourTexte[$k].'</th>';
			
	}
	else { // une semaine entière : lundi à dimanche
		echo '<th><div>'.$jourTexte[$k].' '.date("d", mktime(0,0,0,date("n"),date("d")-$jour+$k,date("y"))).'</div></th>';
	} 
}
 
for ($h = 1; $h < 12; $h++) // colonne principale avec les 2 plages horaires : matin - midi
{
    echo '<tr>
        <th>
            <div>'.$plageHoraire[$h]."h".'</div>
        </th>';
 
        for ($j = 1; $j < 8; $j++) // adaptée au nombre de jour
        {
            echo '<th>';
             // code pour les rdv disponibles
            echo '</th>';
        }
        echo '</tr>';
}
echo '</table>';
?>