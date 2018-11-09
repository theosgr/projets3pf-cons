<?php
	require_once "../../config/config.php";
	require_once PATH_MODELE."/bean/jsonRecherche.php";

	session_start();
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");

	//Initialisation de la liste
	$list = array();

	// Connexion MySQL
	try {
		$chaine = "mysql:host=".HOST.";dbname=".BD.";charset=UTF8";
		$db = new PDO($chaine,LOGIN,PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		throw new PDOException("Erreur de connexion");
	}

  $elements = explode(" ",htmlspecialchars($_GET['specialiste']));
  $chaine = "SELECT civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from Utilisateurs u, Specialite s1, Sous_Specialite s2, Domaine d where type = 2 and u.specialite = s2.id and s2.sousDomaine = s1.id and s1.domaine = d.id and (specialite like :element1 or s2.nom like :element1 or u.nom like :element1 or prenom like :element1 or specialite like :element2 or s2.nom like :element2 or u.nom like :element2 or prenom like :element2) and ville like :ville";

  $stmt = $db->prepare($chaine);
  $stmt->bindParam(":element1", $elements[0]);
  $stmt->bindParam(":element2", $elements[1]);
  $stmt->bindParam(":ville", htmlspecialchars($_GET['ville']));


	// Récupération des données
  $stmt->execute();
	$list = $stmt->fetchAll(PDO::FETCH_CLASS, "jsonRechercheSpecialiste");;

	echo json_encode($list);
?>
