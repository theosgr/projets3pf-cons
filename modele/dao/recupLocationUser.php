<?php
	require_once "../../config/config.php";
	require_once PATH_MODELE."/bean/jsonRecherche.php";

	session_start();
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	
	try {
	    $chaine = "mysql:host=".HOST.";dbname=".BD.";charset=UTF8";
	    $db = new PDO($chaine,LOGIN,PASSWORD);
	    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
	    throw new PDOException("Erreur de connexion");
	}
	$request= "SELECT location FROM Utilisateurs WHERE mail=";
	$reponse = $bdd->query($request);
	echo $reponse;
	
	$reponse->closeCursor();
	
?>
