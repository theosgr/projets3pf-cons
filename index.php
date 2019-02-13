<?php
  session_start();

  require_once "config/config.php";
  require_once PATH_CONTROLEUR."/routeur.php";
  //require_once "config/captcha.php"; bug pour l'instant

  $routeur = new Routeur();
  $routeur->routerRequete();
?>
