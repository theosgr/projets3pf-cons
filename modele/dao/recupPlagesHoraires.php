<?php
require_once "../../config/config.php";
require_once PATH_MODELE."/dao/dao.php";

$modele = new Dao();

$idPro = $_GET['idPro'];
$date = new DateTime($_GET['date']);
$date = $date->format("Y-m-d");
// echo $_GET['idPro'];


if($date !== false)
{
  // date('Y-m-d', $date);
  $result = $modele->getPlageHoraireProDate($idPro, $date);
  $resultJson = json_encode($result);
  // print_r($result);
  echo $resultJson;
}
else {
  // echo "Erreur de format de date";
}


?>
